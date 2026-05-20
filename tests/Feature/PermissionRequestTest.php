<?php

namespace Tests\Feature;

use App\Models\Absence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class PermissionRequestTest extends TestCase
{
    use RefreshDatabase;

    private $intern;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2026, 5, 18, 0, 0, 0));

        $this->intern = $this->createIntern();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }



    public function test_pengajuan_izin_gagal_jika_form_kosong(): void
    {
        $this->actingAs($this->intern, 'interns');

        $response = $this->post(route('permission.store'), [
            'start_date'  => '',
            'end_date'    => '',
            'type'        => '',
            'description' => '',
        ]);

        $response->assertSessionHasErrors(['start_date', 'end_date', 'type', 'description']);
        $this->assertDatabaseCount('permission_requests', 0);
    }

    public function test_pengajuan_izin_gagal_jika_end_date_mendahului_start_date(): void
    {
        $this->actingAs($this->intern, 'interns');

        $response = $this->post(route('permission.store'), [
            'start_date'  => '2026-05-20',
            'end_date'    => '2026-05-18',
            'type'        => 'izin',
            'description' => 'Ada urusan keluarga mendadak',
        ]);

        $response->assertSessionHasErrors(['end_date']);
        $this->assertDatabaseCount('permission_requests', 0);
    }



    public function test_pengajuan_izin_gagal_jika_sudah_ada_absen_di_rentang_tanggal_tersebut(): void
    {
        $this->actingAs($this->intern, 'interns');

        Absence::create([
            'intern_id' => $this->intern->id,
            'date'      => '2026-05-19',
            'check_in'  => '08:00:00',
            'status'    => 'hadir',
            'validation_status' => 'disetujui'
        ]);

        $response = $this->post(route('permission.store'), [
            'start_date'  => '2026-05-18',
            'end_date'    => '2026-05-20',
            'type'        => 'sakit',
            'description' => 'Demam tinggi',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseCount('permission_requests', 0);
        $this->assertDatabaseCount('absences', 1);
    }



    public function test_pengajuan_izin_berhasil_dan_generate_absen_harian_tanpa_hari_minggu(): void
    {
        $this->actingAs($this->intern, 'interns');


        $response = $this->post(route('permission.store'), [
            'start_date'  => '2026-05-22',
            'end_date'    => '2026-05-25',
            'type'        => 'izin',
            'description' => 'Urusan pernikahan kakak kandung',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('permission_requests', [
            'intern_id'  => $this->intern->id,
            'start_date' => '2026-05-22',
            'end_date'   => '2026-05-25',
            'type'       => 'izin',
            'reason'     => 'Urusan pernikahan kakak kandung',
            'status'     => 'pending',
        ]);


        $this->assertDatabaseCount('absences', 3);

        $this->assertDatabaseHas('absences', [
            'intern_id'         => $this->intern->id,
            'date'              => '2026-05-22',
            'status'            => 'izin',
            'validation_status' => 'menunggu',
            'notes_out'         => 'Urusan pernikahan kakak kandung'
        ]);

        $this->assertDatabaseHas('absences', [
            'intern_id'         => $this->intern->id,
            'date'              => '2026-05-25',
            'status'            => 'izin',
            'validation_status' => 'menunggu',
            'notes_out'         => 'Urusan pernikahan kakak kandung'
        ]);

        $this->assertDatabaseMissing('absences', [
            'intern_id' => $this->intern->id,
            'date'      => '2026-05-24',
        ]);
    }
}
