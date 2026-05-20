<?php

namespace Tests\Feature;

use App\Models\Absence;
use App\Models\PermissionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbsenceManagementTest extends TestCase
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


    public function test_admin_bisa_melihat_halaman_manajemen_absensi_tanpa_filter(): void
    {
        $this->actingAs($this->admin, 'admins');

        Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'check_in' => '08:00:00',
            'status' => 'hadir',
            'validation_status' => 'disetujui'
        ]);

        $response = $this->get(route('admin.absence'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.absence');
        $response->assertViewHasAll(['departments', 'absences']);

        $absencesInView = $response->original->getData()['absences'];
        $this->assertCount(1, $absencesInView);
    }

    public function test_halaman_absensi_menyembunyikan_izin_yang_statusnya_masih_pending(): void
    {
        $this->actingAs($this->admin, 'admins');

        $permission = PermissionRequest::create([
            'intern_id' => $this->intern->id,
            'start_date' => '2026-05-19',
            'end_date' => '2026-05-19',
            'type' => 'izin',
            'reason' => 'Keperluan keluarga',
            'status' => 'pending'
        ]);

        Absence::create([
            'intern_id' => $this->intern->id,
            'permission_request_id' => $permission->id,
            'date' => '2026-05-19',
            'status' => 'izin',
            'validation_status' => 'menunggu'
        ]);

        $response = $this->get(route('admin.absence'));

        $absencesInView = $response->original->getData()['absences'];

        $this->assertCount(0, $absencesInView);
    }

    public function test_admin_bisa_mencari_absensi_berdasarkan_nama_intern(): void
    {
        $this->actingAs($this->admin, 'admins');

        Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'status' => 'hadir',
            'validation_status' => 'disetujui'
        ]);

        $internKedua = $this->createIntern([
            'name'     => 'Budi Setiawan',
            'username' => 'budi.setiawan',
            'nin'      => '3516102909049999',
            'phone'    => '081234567890',
        ]);

        Absence::create([
            'intern_id' => $internKedua->id,
            'date' => '2026-05-19',
            'status' => 'hadir',
            'validation_status' => 'disetujui'
        ]);

        $response = $this->get(route('admin.absence', ['search' => 'Budi']));

        $absencesInView = $response->original->getData()['absences'];

        $this->assertCount(1, $absencesInView);
        $this->assertEquals('Budi Setiawan', $absencesInView->first()->intern->name);
    }

    public function test_admin_bisa_memfilter_absensi_berdasarkan_tanggal(): void
    {
        $this->actingAs($this->admin, 'admins');

        $absenMei19 = Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'status' => 'hadir',
            'validation_status' => 'disetujui'
        ]);

        $absenMei20 = Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-20',
            'status' => 'hadir',
            'validation_status' => 'disetujui'
        ]);

        $response = $this->get(route('admin.absence', ['date' => '2026-05-20']));

        $response->assertStatus(200);

        $absencesInView = $response->original->getData()['absences'];

        $this->assertCount(1, $absencesInView);

        $this->assertEquals($absenMei20->id, $absencesInView->first()->id);
    }



    public function test_admin_berhasil_mengubah_status_dan_validasi_absensi_intern(): void
    {
        $this->actingAs($this->admin, 'admins');

        $absence = Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'status' => 'terlambat',
            'validation_status' => 'menunggu'
        ]);

        $response = $this->from(route('admin.absence'))->put(route('admin.absence.update', $absence->id), [
            'status' => 'hadir',
            'validation_status' => 'disetujui',
        ]);

        $response->assertRedirect(route('admin.absence'));

        $this->assertDatabaseHas('absences', [
            'id' => $absence->id,
            'status' => 'hadir',
            'validation_status' => 'disetujui',
            'admin_id' => $this->admin->id,
        ]);
    }

    public function test_update_absensi_gagal_jika_inputan_status_ngaco(): void
    {
        $this->actingAs($this->admin, 'admins');

        $absence = Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'status' => 'terlambat',
            'validation_status' => 'menunggu'
        ]);

        $response = $this->put(route('admin.absence.update', $absence->id), [
            'status' => 'bolos',
            'validation_status' => 'disetujui',
        ]);

        $response->assertSessionHasErrors(['status']);

        $this->assertEquals('terlambat', $absence->fresh()->status);
    }
}
