<?php

namespace Tests\Feature;

use App\Models\Absence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class DashboardInternTest extends TestCase
{
    use RefreshDatabase;

    private $intern;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2026, 5, 19, 12, 0, 0));

        $this->intern = $this->createIntern();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }



    public function test_akses_dashboard_gagal_jika_belum_login(): void
    {
        $response = $this->get(route('intern.dashboard'));

        $response->assertRedirect(route('login.show'));
    }


    public function test_akses_dashboard_berhasil_dan_kalkulasi_summary_akurat(): void
    {
        $this->actingAs($this->intern, 'interns');


        // create absence
        Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-15',
            'status' => 'hadir',
            'validation_status' => 'disetujui'
        ]);

        Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-16',
            'status' => 'terlambat',
            'validation_status' => 'disetujui'
        ]);

        Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-18',
            'status' => 'terlambat',
            'validation_status' => 'menunggu'
        ]);

        Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-19',
            'status' => 'alpha',
            'validation_status' => 'disetujui'
        ]);

        $response = $this->get(route('intern.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('intern.dashboard');

        $response->assertViewHasAll(['absences', 'todayAbsence', 'summary', 'permissions', 'internshipEnded']);

        $summaryData = $response->original->getData()['summary'];

        $this->assertEquals(26, $summaryData['work_days']);
        $this->assertEquals(2, $summaryData['hadir']);
        $this->assertEquals(1, $summaryData['menunggu']);
        $this->assertEquals(1, $summaryData['alpha']);
    }



    public function test_dashboard_bisa_memfilter_data_absensi_dan_izin_berdasarkan_bulan_dan_tahun(): void
    {
        $this->actingAs($this->intern, 'interns');

        Absence::create([
            'intern_id' => $this->intern->id,
            'date' => '2026-05-15',
            'status' => 'hadir',
            'validation_status' => 'disetujui'
        ]);

        $response = $this->get(route('intern.dashboard', ['month' => 6, 'year' => 2026]));

        $response->assertStatus(200);

        $absencesInView = $response->original->getData()['absences'];
        $this->assertCount(0, $absencesInView);
    }



    public function test_dashboard_mendeteksi_jika_periode_magang_intern_telah_berakhir(): void
    {
        $this->actingAs($this->intern, 'interns');

        Carbon::setTestNow(Carbon::create(2026, 6, 2, 9, 0, 0));

        $response = $this->get(route('intern.dashboard'));

        $response->assertStatus(200);

        $internshipEnded = $response->original->getData()['internshipEnded'];
        $this->assertTrue($internshipEnded);
    }
}
