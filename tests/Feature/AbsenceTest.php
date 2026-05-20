<?php

namespace Tests\Feature;

use App\Models\Absence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class AbsenceTest extends TestCase
{
    use RefreshDatabase;

    private $intern;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 7, 0, 0));

        $this->intern = $this->createIntern();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    public function test_checkin_gagal_jika_masa_magang_sudah_berakhir(): void
    {
        $this->actingAs($this->intern, 'interns');

        Carbon::setTestNow(Carbon::create(2026, 6, 15, 8, 0, 0));

        $response = $this->post(route('checkin'));

        $response->assertRedirect();

        $this->assertDatabaseCount('absences', 0);
    }

    public function test_checkin_berhasil_tepat_waktu_status_hadir_dan_disetujui(): void
    {
        $this->actingAs($this->intern, 'interns');

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 7, 45, 0));

        $response = $this->post(route('checkin'));

        $response->assertRedirect();
        $this->assertDatabaseHas('absences', [
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'status' => 'hadir',
            'validation_status' => 'disetujui',
            'check_in' => '07:45:00'
        ]);
    }

    public function test_checkin_berhasil_tetapi_terlambat_status_menunggu_validasi(): void
    {
        $this->actingAs($this->intern, 'interns');

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 8, 15, 0));

        $response = $this->post(route('checkin'));

        $response->assertRedirect();
        $this->assertDatabaseHas('absences', [
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'status' => 'terlambat',
            'validation_status' => 'menunggu',
            'check_in' => '08:15:00'
        ]);
    }

    public function test_checkin_gagal_jika_sudah_absen_pada_hari_yang_sama(): void
    {
        $this->actingAs($this->intern, 'interns');

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 7, 30, 0));

        Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'check_in' => '07:30:00',
            'status' => 'hadir',
            'validation_status' => 'disetujui',
        ]);

        $this->assertDatabaseCount('absences', 1);

        $response = $this->post(route('checkin'));

        $response->assertRedirect();

        $this->assertDatabaseCount('absences', 1);
    }

    public function test_checkout_gagal_jika_belum_pernah_checkin_hari_ini(): void
    {
        $this->actingAs($this->intern, 'interns');

        $response = $this->post(route('checkout'));

        $response->assertRedirect();
        $this->assertDatabaseCount('absences', 0);
    }

    public function test_checkout_berhasil_pulang_sesuai_jam_kerja_tetap_disetujui(): void
    {
        $this->actingAs($this->intern, 'interns');

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 7, 30, 0));
        $this->post(route('checkin'));

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 17, 05, 0));
        $response = $this->post(route('checkout'));

        $response->assertRedirect();

        $absence = Absence::where('intern_id', $this->intern->id)->first();
        $this->assertEquals('17:05:00', $absence->check_out);
        $this->assertEquals('disetujui', $absence->validation_status);
        $this->assertEquals(575, $absence->duration);
    }

    public function test_checkout_gagal_jika_pulang_cepat_tetapi_catatan_kosong(): void
    {
        $this->actingAs($this->intern, 'interns');

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 7, 30, 0));
        $this->post(route('checkin'));

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 15, 0, 0));
        $response = $this->post(route('checkout'), [
            'notes_out' => ''
        ]);

        $response->assertRedirect();

        $absence = Absence::where('intern_id', $this->intern->id)->first();
        $this->assertNull($absence->check_out);
    }

    public function test_checkout_berhasil_pulang_cepat_dengan_catatan_status_menjadi_menunggu(): void
    {
        $this->actingAs($this->intern, 'interns');

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 7, 30, 0));
        $this->post(route('checkin'));

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 15, 0, 0));
        $response = $this->post(route('checkout'), [
            'notes_out' => 'Izin pulang cepat karena ada urusan kampus mendadak'
        ]);

        $response->assertRedirect();

        $absence = Absence::where('intern_id', $this->intern->id)->first();
        $this->assertEquals('15:00:00', $absence->check_out);
        $this->assertEquals('Izin pulang cepat karena ada urusan kampus mendadak', $absence->notes_out);
        $this->assertEquals('menunggu', $absence->validation_status);
    }
}
