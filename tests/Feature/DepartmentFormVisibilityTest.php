<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\FormItem;
use App\Models\FormApproval;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepartmentFormVisibilityTest extends TestCase
{
    use RefreshDatabase;

    protected User $userProduction;
    protected User $staffProduction;
    protected User $userHrga;
    protected User $staffHrga;
    protected User $userDiesAssy;
    protected User $staffProdDies;
    protected User $accounting;
    protected User $warehouse;
    protected User $master;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. User & Staff in Production
        $this->userProduction = User::create([
            'name'       => 'User Production',
            'email'      => 'user_prod@example.com',
            'department' => 'Production',
            'role'       => 'User',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        $this->staffProduction = User::create([
            'name'       => 'Staff Production',
            'email'      => 'staff_prod@example.com',
            'department' => 'Production',
            'role'       => 'Staff',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        // 2. User & Staff in HRGA
        $this->userHrga = User::create([
            'name'       => 'User HRGA',
            'email'      => 'user_hrga@example.com',
            'department' => 'HRGA',
            'role'       => 'User',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        $this->staffHrga = User::create([
            'name'       => 'Staff HRGA',
            'email'      => 'staff_hrga@example.com',
            'department' => 'HRGA',
            'role'       => 'Staff',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        // 2b. User in Dies Assy & Multi-department Staff (Production / Dies Assy)
        $this->userDiesAssy = User::create([
            'name'       => 'User Dies Assy',
            'email'      => 'user_dies@example.com',
            'department' => 'Dies Assy',
            'role'       => 'User',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        $this->staffProdDies = User::create([
            'name'       => 'Staff Prod & Dies Assy',
            'email'      => 'staff_proddies@example.com',
            'department' => 'Production / Dies Assy',
            'role'       => 'Staff (Production / Dies Assy)',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        // 3. Accounting
        $this->accounting = User::create([
            'name'       => 'Accounting Officer',
            'email'      => 'acc@example.com',
            'department' => 'Accounting',
            'role'       => 'Accounting',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        // 4. Warehouse Consumable
        $this->warehouse = User::create([
            'name'       => 'Warehouse Keeper',
            'email'      => 'wh@example.com',
            'department' => 'PPIC Warehouse',
            'role'       => 'Warehouse Consumable',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        // 5. Master / Admin
        $this->master = User::create([
            'name'       => 'Master Admin',
            'email'      => 'master@example.com',
            'department' => 'IT',
            'role'       => 'MASTER',
            'status'     => 'Aktif',
            'password'   => bcrypt('password'),
        ]);

        // Create sample form items for Production
        FormItem::create([
            'form_number'        => '01/PRODUCTION/08-2026',
            'user_id'            => $this->userProduction->id,
            'created_by_name'    => 'User Production',
            'created_by_dept'    => 'Production',
            'kode_barang'        => 'PROD-001',
            'nama_barang'        => 'Mata Bor CNC',
            'harga'              => 150000,
            'estimasi_usia_pakai'=> '30 Hari',
            'kategori_penggunaan'=> 'Produksi',
            'kategori_ukuran'    => 'Sedang',
            'min'                => 5,
            'titik_order'        => 10,
            'max'                => 20,
            'lead_time'          => '3 Hari',
            'is_b3'              => false,
            'is_non_b3'          => true,
        ]);

        // Create sample form items for Dies Assy
        FormItem::create([
            'form_number'        => '01/DIES ASSY/08-2026',
            'user_id'            => $this->userDiesAssy->id,
            'created_by_name'    => 'User Dies Assy',
            'created_by_dept'    => 'Dies Assy',
            'kode_barang'        => 'DIES-001',
            'nama_barang'        => 'Guide Pin Dies',
            'harga'              => 275000,
            'estimasi_usia_pakai'=> '60 Hari',
            'kategori_penggunaan'=> 'Dies Mold',
            'kategori_ukuran'    => 'Sedang',
            'min'                => 2,
            'titik_order'        => 4,
            'max'                => 10,
            'lead_time'          => '5 Hari',
            'is_b3'              => false,
            'is_non_b3'          => true,
        ]);

        // Create sample form items for HRGA
        FormItem::create([
            'form_number'        => '01/HRGA/08-2026',
            'user_id'            => $this->userHrga->id,
            'created_by_name'    => 'User HRGA',
            'created_by_dept'    => 'HRGA',
            'kode_barang'        => 'HRGA-001',
            'nama_barang'        => 'Kertas A4 PaperOne',
            'harga'              => 55000,
            'estimasi_usia_pakai'=> '14 Hari',
            'kategori_penggunaan'=> 'Kantor',
            'kategori_ukuran'    => 'Kecil',
            'min'                => 10,
            'titik_order'        => 20,
            'max'                => 50,
            'lead_time'          => '2 Hari',
            'is_b3'              => false,
            'is_non_b3'          => true,
        ]);
    }

    /**
     * Test User in Production can only see Production forms.
     */
    public function test_user_in_production_can_only_see_own_department_forms(): void
    {
        $response = $this->actingAs($this->userProduction)->get(route('form-registrasi'));

        $response->assertStatus(200);
        $response->assertSee('01/PRODUCTION/08-2026');
        $response->assertSee('Mata Bor CNC');

        // Must NOT see HRGA forms
        $response->assertDontSee('01/HRGA/08-2026');
        $response->assertDontSee('Kertas A4 PaperOne');
    }

    /**
     * Test Staff in Production can only see Production forms.
     */
    public function test_staff_in_production_can_only_see_own_department_forms(): void
    {
        $response = $this->actingAs($this->staffProduction)->get(route('form-registrasi'));

        $response->assertStatus(200);
        $response->assertSee('01/PRODUCTION/08-2026');
        $response->assertSee('Mata Bor CNC');

        // Must NOT see HRGA forms
        $response->assertDontSee('01/HRGA/08-2026');
        $response->assertDontSee('Kertas A4 PaperOne');
    }

    /**
     * Test User in HRGA can only see HRGA forms.
     */
    public function test_user_in_hrga_can_only_see_hrga_forms(): void
    {
        $response = $this->actingAs($this->userHrga)->get(route('form-registrasi'));

        $response->assertStatus(200);
        $response->assertSee('01/HRGA/08-2026');
        $response->assertSee('Kertas A4 PaperOne');

        // Must NOT see Production forms
        $response->assertDontSee('01/PRODUCTION/08-2026');
        $response->assertDontSee('Mata Bor CNC');
    }

    /**
     * Test Accounting role can see forms from all departments.
     */
    public function test_accounting_can_see_forms_from_all_departments(): void
    {
        $response = $this->actingAs($this->accounting)->get(route('form-registrasi'));

        $response->assertStatus(200);
        $response->assertSee('01/PRODUCTION/08-2026');
        $response->assertSee('Mata Bor CNC');
        $response->assertSee('01/HRGA/08-2026');
        $response->assertSee('Kertas A4 PaperOne');
    }

    /**
     * Test Warehouse Consumable role can see forms from all departments.
     */
    public function test_warehouse_consumable_can_see_forms_from_all_departments(): void
    {
        $response = $this->actingAs($this->warehouse)->get(route('form-registrasi'));

        $response->assertStatus(200);
        $response->assertSee('01/PRODUCTION/08-2026');
        $response->assertSee('Mata Bor CNC');
        $response->assertSee('01/HRGA/08-2026');
        $response->assertSee('Kertas A4 PaperOne');
    }

    /**
     * Test Master/Admin can see forms from all departments.
     */
    public function test_master_can_see_forms_from_all_departments(): void
    {
        $response = $this->actingAs($this->master)->get(route('form-registrasi'));

        $response->assertStatus(200);
        $response->assertSee('01/PRODUCTION/08-2026');
        $response->assertSee('Mata Bor CNC');
        $response->assertSee('01/HRGA/08-2026');
        $response->assertSee('Kertas A4 PaperOne');
    }

    /**
     * Test Staff from Production cannot approve HRGA forms.
     */
    public function test_staff_cannot_approve_other_department_form(): void
    {
        $response = $this->actingAs($this->staffProduction)
            ->postJson(route('form-registrasi.approve'), [
                'form_number' => '01/HRGA/08-2026',
                'role'        => 'staff',
                'name'        => 'Staff Production',
                'comment'     => 'Coba approve dept lain',
            ]);

        $response->assertStatus(403);
    }

    /**
     * Test Staff from Production can approve Production form.
     */
    public function test_staff_can_approve_own_department_form(): void
    {
        $response = $this->actingAs($this->staffProduction)
            ->postJson(route('form-registrasi.approve'), [
                'form_number' => '01/PRODUCTION/08-2026',
                'role'        => 'staff',
                'name'        => 'Staff Production',
                'comment'     => 'Disetujui untuk produksi',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $approval = FormApproval::where('form_number', '01/PRODUCTION/08-2026')->first();
        $this->assertNotNull($approval);
        $this->assertEquals('APPROVAL ACCOUNTING', $approval->status);
        $this->assertEquals('Staff Production', $approval->staff_signer_name);
    }

    /**
     * Test User/Staff cannot delete form belonging to other department.
     */
    public function test_user_cannot_delete_form_of_other_department(): void
    {
        $response = $this->actingAs($this->userProduction)
            ->delete(route('form-registrasi.delete-checksheet'), [
                'form_number' => '01/HRGA/08-2026',
            ]);

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('form_items', [
            'form_number' => '01/HRGA/08-2026',
            'nama_barang' => 'Kertas A4 PaperOne',
        ]);
    }

    /**
     * Test User/Staff cannot delete individual item belonging to other department.
     */
    public function test_user_cannot_delete_item_of_other_department(): void
    {
        $hrgaItem = FormItem::where('form_number', '01/HRGA/08-2026')->first();

        $response = $this->actingAs($this->userProduction)
            ->delete(route('form-registrasi.delete', $hrgaItem->id));

        $response->assertSessionHas('error');
        $this->assertDatabaseHas('form_items', [
            'id' => $hrgaItem->id,
            'deleted_at' => null,
        ]);
    }

    /**
     * Test restricted user querying another department form via query parameter gets sanitized.
     */
    public function test_restricted_user_query_param_sanitization(): void
    {
        $response = $this->actingAs($this->userProduction)
            ->get(route('form-registrasi', ['form' => '01/HRGA/08-2026']));

        $response->assertStatus(200);
        // Should NOT see HRGA items
        $response->assertDontSee('Kertas A4 PaperOne');
        $response->assertSee('Mata Bor CNC');
    }

    /**
     * Test complete approval cycle through Staff, Accounting, and Warehouse registration.
     */
    public function test_full_approval_and_registration_flow(): void
    {
        // 1. Staff approves
        $this->actingAs($this->staffProduction)
            ->postJson(route('form-registrasi.approve'), [
                'form_number' => '01/PRODUCTION/08-2026',
                'role'        => 'staff',
                'name'        => 'Staff Production',
                'comment'     => 'Staff approved',
            ])->assertStatus(200);

        // 2. Accounting approves
        $this->actingAs($this->accounting)
            ->postJson(route('form-registrasi.approve'), [
                'form_number' => '01/PRODUCTION/08-2026',
                'role'        => 'accounting',
                'name'        => 'Accounting Officer',
                'comment'     => 'Accounting approved',
            ])->assertStatus(200);

        // 3. Warehouse registers
        $this->actingAs($this->warehouse)
            ->postJson(route('form-registrasi.approve'), [
                'form_number' => '01/PRODUCTION/08-2026',
                'role'        => 'warehouse',
                'name'        => 'Warehouse Keeper',
                'comment'     => 'Registered into warehouse',
            ])->assertStatus(200);

        $approval = FormApproval::where('form_number', '01/PRODUCTION/08-2026')->first();
        $this->assertNotNull($approval);
        $this->assertEquals('TELAH DIDAFTARKAN', $approval->status);
        $this->assertNotNull($approval->warehouse_signed_at);
        $this->assertEquals('Warehouse Keeper', $approval->warehouse_signer_name);
    }

    /**
     * Test Staff (Production / Dies Assy) can see forms from BOTH Production and Dies Assy departments.
     */
    public function test_staff_production_dies_assy_can_see_both_production_and_dies_assy_forms(): void
    {
        $response = $this->actingAs($this->staffProdDies)->get(route('form-registrasi'));

        $response->assertStatus(200);
        // Production form & items
        $response->assertSee('01/PRODUCTION/08-2026');
        $response->assertSee('Mata Bor CNC');

        // Dies Assy form & items
        $response->assertSee('01/DIES ASSY/08-2026');
        $response->assertSee('Guide Pin Dies');

        // Must NOT see HRGA forms
        $response->assertDontSee('01/HRGA/08-2026');
        $response->assertDontSee('Kertas A4 PaperOne');
    }

    /**
     * Test Staff (Production / Dies Assy) can approve Production forms.
     */
    public function test_staff_production_dies_assy_can_approve_production_form(): void
    {
        $response = $this->actingAs($this->staffProdDies)
            ->postJson(route('form-registrasi.approve'), [
                'form_number' => '01/PRODUCTION/08-2026',
                'role'        => 'staff',
                'name'        => 'Staff Prod & Dies Assy',
                'comment'     => 'Disetujui untuk production',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $approval = FormApproval::where('form_number', '01/PRODUCTION/08-2026')->first();
        $this->assertNotNull($approval);
        $this->assertEquals('APPROVAL ACCOUNTING', $approval->status);
        $this->assertEquals('Staff Prod & Dies Assy', $approval->staff_signer_name);
    }

    /**
     * Test Staff (Production / Dies Assy) can approve Dies Assy forms.
     */
    public function test_staff_production_dies_assy_can_approve_dies_assy_form(): void
    {
        $response = $this->actingAs($this->staffProdDies)
            ->postJson(route('form-registrasi.approve'), [
                'form_number' => '01/DIES ASSY/08-2026',
                'role'        => 'staff',
                'name'        => 'Staff Prod & Dies Assy',
                'comment'     => 'Disetujui untuk dies assy',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $approval = FormApproval::where('form_number', '01/DIES ASSY/08-2026')->first();
        $this->assertNotNull($approval);
        $this->assertEquals('APPROVAL ACCOUNTING', $approval->status);
        $this->assertEquals('Staff Prod & Dies Assy', $approval->staff_signer_name);
    }

    /**
     * Test Staff (Production / Dies Assy) cannot approve HRGA forms.
     */
    public function test_staff_production_dies_assy_cannot_approve_hrga_form(): void
    {
        $response = $this->actingAs($this->staffProdDies)
            ->postJson(route('form-registrasi.approve'), [
                'form_number' => '01/HRGA/08-2026',
                'role'        => 'staff',
                'name'        => 'Staff Prod & Dies Assy',
                'comment'     => 'Mencoba approve HRGA',
            ]);

        $response->assertStatus(403);
    }
}
