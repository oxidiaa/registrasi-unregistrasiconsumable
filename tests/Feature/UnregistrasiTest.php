<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\UnregistrasiItem;
use App\Models\UnregistrasiApproval;
use App\Models\UnregistrasiComment;

class UnregistrasiTest extends TestCase
{
    use RefreshDatabase;

    private User $master;
    private User $userProd;
    private User $staffProd;
    private User $userHrga;
    private User $whConsumable;

    protected function setUp(): void
    {
        parent::setUp();

        $this->master = User::factory()->create([
            'name' => 'Mr Master',
            'department' => 'IT / Master',
            'role' => 'Master',
        ]);

        $this->userProd = User::factory()->create([
            'name' => 'Mrs Febi',
            'department' => 'Production',
            'role' => 'User',
        ]);

        $this->staffProd = User::factory()->create([
            'name' => 'Mr Fadjar',
            'department' => 'Production',
            'role' => 'Staff',
        ]);

        $this->userHrga = User::factory()->create([
            'name' => 'Mrs Maya',
            'department' => 'HRGA',
            'role' => 'User',
        ]);

        $this->whConsumable = User::factory()->create([
            'name' => 'Mr Ronni',
            'department' => 'Warehouse',
            'role' => 'Warehouse Consumable',
        ]);
    }

    public function test_user_can_access_unregistrasi_page_and_create_form_item(): void
    {
        $res = $this->actingAs($this->userProd)->get('/form-unregistrasi');
        $res->assertStatus(200);
        $res->assertSee('Form Unregistrasi Barang Consumable', false);
        $res->assertSee('KODE BARANG', false);
        $res->assertSee('SPESIFIKASI', false);
        $res->assertSee('KATEGORI', false);
        $res->assertSee('KETERANGAN', false);

        $formNo = '01/PRODUCTION/08-2026';
        $postRes = $this->actingAs($this->userProd)->post('/form-unregistrasi', [
            'form_number' => $formNo,
            'kode_barang' => 'SBM-001999',
            'nama_barang' => 'KAIN MAJUN MERAH',
            'spesifikasi' => 'Ukuran 40x40 cm',
            'kategori'    => 'CONSUMABLE',
            'keterangan'  => 'Discontinue karena digantikan tipe baru',
        ]);

        $postRes->assertRedirect(route('form-unregistrasi', ['form' => $formNo]));

        $this->assertDatabaseHas('unregistrasi_items', [
            'form_number' => $formNo,
            'kode_barang' => 'SBM-001999',
            'nama_barang' => 'KAIN MAJUN MERAH',
        ]);

        $this->assertDatabaseHas('unregistrasi_approvals', [
            'form_number' => $formNo,
            'status'      => 'Butuh Approval Staff / Section Head',
        ]);
    }

    public function test_department_isolation_on_unregistrasi_forms(): void
    {
        $prodForm = '01/PRODUCTION/08-2026';
        UnregistrasiItem::create([
            'form_number'     => $prodForm,
            'user_id'         => $this->userProd->id,
            'created_by_name' => $this->userProd->name,
            'created_by_dept' => $this->userProd->department,
            'kode_barang'     => 'PROD-01',
            'nama_barang'     => 'Barang Production',
        ]);

        $hrgaForm = '01/HRGA/08-2026';
        UnregistrasiItem::create([
            'form_number'     => $hrgaForm,
            'user_id'         => $this->userHrga->id,
            'created_by_name' => $this->userHrga->name,
            'created_by_dept' => $this->userHrga->department,
            'kode_barang'     => 'HRGA-01',
            'nama_barang'     => 'Barang HRGA',
        ]);

        // User Production can only see Production form
        $resProd = $this->actingAs($this->userProd)->get('/form-unregistrasi');
        $resProd->assertStatus(200);
        $resProd->assertSee('PROD-01', false);
        $resProd->assertDontSee('HRGA-01', false);

        // User HRGA cannot add item to Production form
        $resForbidden = $this->actingAs($this->userHrga)->post('/form-unregistrasi', [
            'form_number' => $prodForm,
            'kode_barang' => 'HRGA-999',
            'nama_barang' => 'Barang Ilegal',
            'spesifikasi' => 'Spesifikasi Ilegal',
            'kategori'    => 'CONSUMABLE',
            'keterangan'  => 'Alasan Ilegal',
        ]);
        $resForbidden->assertRedirect();
        $this->assertDatabaseMissing('unregistrasi_items', ['nama_barang' => 'Barang Ilegal']);
    }

    public function test_3_step_approval_flow_staff_and_warehouse_discontinue(): void
    {
        $formNo = '01/PRODUCTION/08-2026';
        UnregistrasiItem::create([
            'form_number'     => $formNo,
            'user_id'         => $this->userProd->id,
            'created_by_name' => $this->userProd->name,
            'created_by_dept' => $this->userProd->department,
            'kode_barang'     => 'UNREG-01',
            'nama_barang'     => 'Item Discontinue Flow',
        ]);

        // Step 1: Form initially created, status is 'Butuh Approval Staff / Section Head'
        $approval = UnregistrasiApproval::create([
            'form_number'      => $formNo,
            'user_id'          => $this->userProd->id,
            'requestor_name'   => $this->userProd->name,
            'requestor_dept'   => $this->userProd->department,
            'form_date'        => date('d-m-Y'),
            'status'           => 'Butuh Approval Staff / Section Head',
            'user_signed_at'   => now(),
            'user_signer_name' => $this->userProd->name,
        ]);

        // Warehouse cannot approve before Staff
        $resWhEarly = $this->actingAs($this->whConsumable)->postJson('/form-unregistrasi/approve', [
            'form_number' => $formNo,
            'role'        => 'warehouse',
            'name'        => $this->whConsumable->name,
        ]);
        $resWhEarly->assertStatus(422);

        // Step 2: Staff Production approves
        $resStaff = $this->actingAs($this->staffProd)->postJson('/form-unregistrasi/approve', [
            'form_number' => $formNo,
            'role'        => 'staff',
            'name'        => $this->staffProd->name,
            'comment'     => 'Disetujui staff production',
        ]);
        $resStaff->assertStatus(200);

        $this->assertDatabaseHas('unregistrasi_approvals', [
            'form_number'       => $formNo,
            'status'            => 'Butuh Verifikasi Warehouse Consumable',
            'staff_signer_name' => $this->staffProd->name,
        ]);

        // Step 3: Warehouse Consumable discontinues
        $resWh = $this->actingAs($this->whConsumable)->postJson('/form-unregistrasi/approve', [
            'form_number' => $formNo,
            'role'        => 'warehouse',
            'name'        => $this->whConsumable->name,
            'comment'     => 'Telah dihapus dari ERP sistem',
        ]);
        $resWh->assertStatus(200);

        $this->assertDatabaseHas('unregistrasi_approvals', [
            'form_number'           => $formNo,
            'status'                => 'Telah Discontinue oleh Warehouse Consumable',
            'warehouse_signer_name' => $this->whConsumable->name,
        ]);
    }

    public function test_comments_on_unregistrasi_forms_and_only_master_can_delete(): void
    {
        $formNo = '01/PRODUCTION/08-2026';
        UnregistrasiItem::create([
            'form_number'     => $formNo,
            'user_id'         => $this->userProd->id,
            'created_by_name' => $this->userProd->name,
            'created_by_dept' => $this->userProd->department,
            'kode_barang'     => 'COM-01',
            'nama_barang'     => 'Item Comment Test',
        ]);

        // User posts comment
        $resComment = $this->actingAs($this->userProd)->postJson('/form-unregistrasi/comments', [
            'form_number' => $formNo,
            'comment'     => 'Mohon diproses unregistrasinya.',
        ]);
        $resComment->assertStatus(200);

        $comment = UnregistrasiComment::where('form_number', $formNo)->first();
        $this->assertNotNull($comment);

        // Author/regular user cannot delete comment (only Master can)
        $resDelUser = $this->actingAs($this->userProd)->deleteJson('/form-unregistrasi/comments/' . $comment->id);
        $resDelUser->assertStatus(403);
        $this->assertDatabaseHas('unregistrasi_comments', ['id' => $comment->id]);

        // Master deletes comment
        $resDelMaster = $this->actingAs($this->master)->deleteJson('/form-unregistrasi/comments/' . $comment->id);
        $resDelMaster->assertStatus(200);
        $this->assertDatabaseMissing('unregistrasi_comments', ['id' => $comment->id]);
    }

    public function test_deleting_unregistrasi_form_and_item_cascading(): void
    {
        $formNo = '01/PRODUCTION/08-2026';
        $item = UnregistrasiItem::create([
            'form_number'     => $formNo,
            'user_id'         => $this->userProd->id,
            'created_by_name' => $this->userProd->name,
            'created_by_dept' => $this->userProd->department,
            'kode_barang'     => 'DEL-01',
            'nama_barang'     => 'Item to Delete',
        ]);

        UnregistrasiApproval::create([
            'form_number' => $formNo,
            'status'      => 'Butuh Approval Staff / Section Head',
        ]);

        UnregistrasiComment::create([
            'form_number' => $formNo,
            'user_id'     => $this->userProd->id,
            'user_name'   => $this->userProd->name,
            'comment'     => 'Comment to be deleted',
        ]);

        // Delete entire form
        $res = $this->actingAs($this->master)->delete('/form-unregistrasi/form/delete', [
            'form_number' => $formNo,
        ]);
        $res->assertRedirect();

        $this->assertDatabaseCount('unregistrasi_items', 0);
        $this->assertDatabaseCount('unregistrasi_approvals', 0);
        $this->assertDatabaseCount('unregistrasi_comments', 0);
    }

    public function test_master_user_can_access_unregistrasi_and_view_account_master(): void
    {
        $res = $this->actingAs($this->master)->get('/form-unregistrasi');
        $res->assertStatus(200);
        $res->assertSee('Account Master', false);
        $res->assertSee('Account Master Management', false);
    }

    public function test_all_fields_are_required_when_adding_unregistrasi_item(): void
    {
        $res = $this->actingAs($this->userProd)->post('/form-unregistrasi', [
            'form_number' => '01/PRODUCTION/08-2026',
            'kode_barang' => '',
            'nama_barang' => '',
            'spesifikasi' => '',
            'kategori'    => '',
            'keterangan'  => '',
        ]);

        $res->assertSessionHasErrors(['kode_barang', 'nama_barang', 'spesifikasi', 'kategori', 'keterangan']);
    }
}
