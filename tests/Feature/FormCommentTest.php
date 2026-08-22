<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\FormItem;
use App\Models\FormApproval;
use App\Models\FormComment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormCommentTest extends TestCase
{
    use RefreshDatabase;

    private User $userProd;
    private User $staffProd;
    private User $accounting;
    private User $warehouse;
    private User $master;
    private User $userHrga;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userProd = User::factory()->create([
            'name' => 'User Production',
            'department' => 'Production',
            'role' => 'User',
        ]);

        $this->staffProd = User::factory()->create([
            'name' => 'Staff Production',
            'department' => 'Production',
            'role' => 'Staff',
        ]);

        $this->accounting = User::factory()->create([
            'name' => 'Accounting User',
            'department' => 'Accounting',
            'role' => 'Accounting',
        ]);

        $this->warehouse = User::factory()->create([
            'name' => 'Warehouse User',
            'department' => 'Warehouse Consumable',
            'role' => 'Warehouse Consumable',
        ]);

        $this->master = User::factory()->create([
            'name' => 'Master Admin',
            'department' => 'IT',
            'role' => 'MASTER',
        ]);

        $this->userHrga = User::factory()->create([
            'name' => 'User HRGA',
            'department' => 'HRGA',
            'role' => 'User',
        ]);
    }

    private function createForm(string $formNo, User $creator): void
    {
        FormItem::create([
            'form_number' => $formNo,
            'user_id' => $creator->id,
            'created_by_name' => $creator->name,
            'created_by_dept' => $creator->department,
            'kode_barang' => 'BRG-001',
            'nama_barang' => 'Barang Test ' . $creator->department,
            'harga' => 100000,
            'estimasi_usia_pakai' => '30 Hari',
            'kategori_penggunaan' => 'Produksi',
            'kategori_ukuran' => 'Kecil',
            'min' => 1,
            'titik_order' => 2,
            'max' => 5,
            'lead_time' => '3 Hari',
            'is_b3' => false,
            'is_non_b3' => true,
        ]);

        FormApproval::create([
            'form_number' => $formNo,
            'user_id' => $creator->id,
            'requestor_name' => $creator->name,
            'requestor_dept' => $creator->department,
            'form_date' => date('d-m-Y'),
            'status' => 'Butuh Approval Staff / Section Head',
            'user_signed_at' => now(),
            'user_signer_name' => $creator->name,
            'user_comment' => 'Pengajuan form',
        ]);
    }

    public function test_all_roles_can_post_comments(): void
    {
        $formNo = '01/PRODUCTION/08-2026';
        $this->createForm($formNo, $this->userProd);

        // 1. User posts comment
        $res1 = $this->actingAs($this->userProd)->postJson('/form-registrasi/comments', [
            'form_number' => $formNo,
            'comment' => 'Komentar dari User Production',
        ]);
        $res1->assertStatus(200);
        $res1->assertJsonPath('success', true);
        $res1->assertJsonPath('comment.user_name', 'User Production');

        // 2. Staff posts comment
        $res2 = $this->actingAs($this->staffProd)->postJson('/form-registrasi/comments', [
            'form_number' => $formNo,
            'comment' => 'Komentar dari Staff: mohon lengkapi spesifikasi',
        ]);
        $res2->assertStatus(200);
        $res2->assertJsonPath('success', true);
        $res2->assertJsonPath('comment.user_role', 'Staff');

        // 3. Accounting posts comment
        $res3 = $this->actingAs($this->accounting)->postJson('/form-registrasi/comments', [
            'form_number' => $formNo,
            'comment' => 'Komentar dari Accounting: budget tersedia',
        ]);
        $res3->assertStatus(200);
        $res3->assertJsonPath('success', true);

        // 4. Warehouse posts comment
        $res4 = $this->actingAs($this->warehouse)->postJson('/form-registrasi/comments', [
            'form_number' => $formNo,
            'comment' => 'Komentar dari Warehouse: siap registrasi ke ERP',
        ]);
        $res4->assertStatus(200);
        $res4->assertJsonPath('success', true);

        // 5. Master posts comment
        $res5 = $this->actingAs($this->master)->postJson('/form-registrasi/comments', [
            'form_number' => $formNo,
            'comment' => 'Komentar dari Master Admin',
        ]);
        $res5->assertStatus(200);
        $res5->assertJsonPath('success', true);

        $this->assertDatabaseCount('form_comments', 5);
        $this->assertDatabaseHas('form_comments', [
            'form_number' => $formNo,
            'comment' => 'Komentar dari User Production',
        ]);
    }

    public function test_comment_validation_fails_on_empty_comment(): void
    {
        $formNo = '01/PRODUCTION/08-2026';
        $this->createForm($formNo, $this->userProd);

        $res = $this->actingAs($this->userProd)->postJson('/form-registrasi/comments', [
            'form_number' => $formNo,
            'comment' => '',
        ]);
        $res->assertStatus(422);
        $res->assertJsonValidationErrors(['comment']);
    }

    public function test_restricted_user_cannot_comment_on_other_department_form(): void
    {
        $prodForm = '01/PRODUCTION/08-2026';
        $this->createForm($prodForm, $this->userProd);

        // User HRGA cannot comment on Production form
        $res = $this->actingAs($this->userHrga)->postJson('/form-registrasi/comments', [
            'form_number' => $prodForm,
            'comment' => 'Komentar nyasar',
        ]);
        $res->assertStatus(403);
    }

    public function test_author_and_master_can_delete_comment_but_others_cannot(): void
    {
        $formNo = '01/PRODUCTION/08-2026';
        $this->createForm($formNo, $this->userProd);

        $comment = FormComment::create([
            'form_number' => $formNo,
            'user_id' => $this->userProd->id,
            'user_name' => $this->userProd->name,
            'user_dept' => $this->userProd->department,
            'user_role' => $this->userProd->role,
            'comment' => 'Komentar untuk dihapus',
        ]);

        // Another user (e.g. staffProd who is not author and not master) cannot delete it
        $resOther = $this->actingAs($this->staffProd)->deleteJson('/form-registrasi/comments/' . $comment->id);
        $resOther->assertStatus(403);
        $this->assertDatabaseHas('form_comments', ['id' => $comment->id]);

        // Author can delete it
        $resAuthor = $this->actingAs($this->userProd)->deleteJson('/form-registrasi/comments/' . $comment->id);
        $resAuthor->assertStatus(200);
        $this->assertDatabaseMissing('form_comments', ['id' => $comment->id]);

        // Create another comment and verify Master can delete it
        $comment2 = FormComment::create([
            'form_number' => $formNo,
            'user_id' => $this->userProd->id,
            'user_name' => $this->userProd->name,
            'user_dept' => $this->userProd->department,
            'user_role' => $this->userProd->role,
            'comment' => 'Komentar 2',
        ]);

        $resMaster = $this->actingAs($this->master)->deleteJson('/form-registrasi/comments/' . $comment2->id);
        $resMaster->assertStatus(200);
        $this->assertDatabaseMissing('form_comments', ['id' => $comment2->id]);
    }

    public function test_deleting_form_checksheet_cleans_up_comments(): void
    {
        $formNo = '01/PRODUCTION/08-2026';
        $this->createForm($formNo, $this->userProd);

        FormComment::create([
            'form_number' => $formNo,
            'user_id' => $this->userProd->id,
            'user_name' => $this->userProd->name,
            'user_dept' => $this->userProd->department,
            'user_role' => $this->userProd->role,
            'comment' => 'Komentar form production',
        ]);

        $this->assertDatabaseCount('form_comments', 1);

        $this->actingAs($this->master)->delete('/form-registrasi/form/delete', [
            'form_number' => $formNo,
        ]);

        $this->assertDatabaseCount('form_comments', 0);
    }

    public function test_form_registrasi_view_renders_comments_successfully(): void
    {
        $formNo = '01/PRODUCTION/08-2026';
        $this->createForm($formNo, $this->userProd);

        FormComment::create([
            'form_number' => $formNo,
            'user_id' => $this->userProd->id,
            'user_name' => $this->userProd->name,
            'user_dept' => $this->userProd->department,
            'user_role' => $this->userProd->role,
            'comment' => 'Catatan tes render blade',
        ]);

        $res = $this->actingAs($this->userProd)->get('/form-registrasi');
        $res->assertStatus(200);
        $res->assertSee('Diskusi & Komentar Form', false);
        $res->assertSee('Catatan tes render blade', false);
    }
}
