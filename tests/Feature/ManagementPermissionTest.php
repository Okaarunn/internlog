<?php

namespace Tests\Feature;

use App\Models\Absence;
use App\Models\PermissionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagementPermissionTest extends TestCase
{
    use RefreshDatabase;

    private $admin;
    private $intern;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createAdmin();
        $this->intern = $this->createIntern();
    }



    public function test_admin_bisa_melihat_halaman_manajemen_perizinan_tanpa_filter(): void
    {
        $this->actingAs($this->admin, 'admins');

        PermissionRequest::create([
            'intern_id' => $this->intern->id,
            'start_date' => '2026-05-20',
            'end_date' => '2026-05-21',
            'type' => 'sakit',
            'reason' => 'Demam tinggi',
            'status' => 'pending'
        ]);

        $response = $this->get(route('admin.permission'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.permission');
        $response->assertViewHas('permissions');

        $permissionsInView = $response->original->getData()['permissions'];
        $this->assertCount(1, $permissionsInView);
    }

    public function test_admin_bisa_mencari_perizinan_berdasarkan_nama_peserta(): void
    {
        $this->actingAs($this->admin, 'admins');

        PermissionRequest::create([
            'intern_id' => $this->intern->id,
            'start_date' => '2026-05-20',
            'end_date' => '2026-05-20',
            'type' => 'izin',
            'reason' => 'Acara keluarga',
            'status' => 'pending'
        ]);

        $internKedua = $this->createIntern([
            'name' => 'Budi Setiawan',
            'username' => 'budi.setiawan',
            'nin' => '3516102909041111',
            'phone' => '081234567891'
        ]);

        PermissionRequest::create([
            'intern_id' => $internKedua->id,
            'start_date' => '2026-05-20',
            'end_date' => '2026-05-20',
            'type' => 'izin',
            'reason' => 'Urusan kampus',
            'status' => 'pending'
        ]);

        $response = $this->get(route('admin.permission', ['search' => 'Budi']));

        $permissionsInView = $response->original->getData()['permissions'];

        $this->assertCount(1, $permissionsInView);
        $this->assertEquals('Budi Setiawan', $permissionsInView->first()->intern->name);
    }

    public function test_admin_bisa_memfilter_perizinan_berdasarkan_status(): void
    {
        $this->actingAs($this->admin, 'admins');

        PermissionRequest::create([
            'intern_id' => $this->intern->id,
            'start_date' => '2026-05-20',
            'end_date' => '2026-05-20',
            'type' => 'izin',
            'reason' => 'Kepentingan A',
            'status' => 'approved'
        ]);

        PermissionRequest::create([
            'intern_id' => $this->intern->id,
            'start_date' => '2026-05-21',
            'end_date' => '2026-05-21',
            'type' => 'sakit',
            'reason' => 'Kepentingan B',
            'status' => 'pending'
        ]);

        $response = $this->get(route('admin.permission', ['status' => 'approved']));

        $permissionsInView = $response->original->getData()['permissions'];

        $this->assertCount(1, $permissionsInView);
        $this->assertEquals('approved', $permissionsInView->first()->status);
    }



    public function test_admin_menyetujui_perizinan_dan_mengubah_status_absensi_terkait(): void
    {
        $this->actingAs($this->admin, 'admins');

        $permission = PermissionRequest::create([
            'intern_id' => $this->intern->id,
            'start_date' => '2026-05-20',
            'end_date' => '2026-05-20',
            'type' => 'izin',
            'reason' => 'Izin Wisuda',
            'status' => 'pending'
        ]);

        $absence = Absence::create([
            'intern_id' => $this->intern->id,
            'permission_request_id' => $permission->id,
            'date' => '2026-05-20',
            'status' => 'izin',
            'validation_status' => 'menunggu'
        ]);

        $response = $this->from(route('admin.permission'))
            ->put(route('admin.permission.update', $permission->id), [
                'status' => 'approved'
            ]);

        $response->assertRedirect(route('admin.permission'));

        $this->assertDatabaseHas('permission_requests', [
            'id' => $permission->id,
            'status' => 'approved',
            'approved_by' => $this->admin->id
        ]);

        $this->assertDatabaseHas('absences', [
            'id' => $absence->id,
            'validation_status' => 'disetujui',
            'admin_id' => $this->admin->id
        ]);
    }

    public function test_admin_menolak_perizinan_dan_menghapus_absensi_terkait(): void
    {
        $this->actingAs($this->admin, 'admins');

        $permission = PermissionRequest::create([
            'intern_id' => $this->intern->id,
            'start_date' => '2026-05-20',
            'end_date' => '2026-05-20',
            'type' => 'izin',
            'reason' => 'Alasan tidak logis',
            'status' => 'pending'
        ]);

        $absence = Absence::create([
            'intern_id' => $this->intern->id,
            'permission_request_id' => $permission->id,
            'date' => '2026-05-20',
            'status' => 'izin',
            'validation_status' => 'menunggu'
        ]);

        $response = $this->from(route('admin.permission'))
            ->put(route('admin.permission.update', $permission->id), [
                'status' => 'rejected'
            ]);

        $response->assertRedirect(route('admin.permission'));

        $this->assertDatabaseHas('permission_requests', [
            'id' => $permission->id,
            'status' => 'rejected',
            'approved_by' => $this->admin->id
        ]);


        $this->assertSoftDeleted('absences', [
            'id' => $absence->id
        ]);
    }

    public function test_update_perizinan_gagal_jika_status_tidak_sesuai_enum(): void
    {
        $this->actingAs($this->admin, 'admins');

        $permission = PermissionRequest::create([
            'intern_id' => $this->intern->id,
            'start_date' => '2026-05-20',
            'end_date' => '2026-05-20',
            'type' => 'izin',
            'reason' => 'Sakit kepala',
            'status' => 'pending'
        ]);

        $response = $this->put(route('admin.permission.update', $permission->id), [
            'status' => 'kabur'
        ]);

        $response->assertSessionHasErrors(['status']);

        $this->assertEquals('pending', $permission->fresh()->status);
    }
}
