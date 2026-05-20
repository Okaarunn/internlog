<?php

namespace Tests\Feature;

use App\Models\Absence;
use App\Models\Department;
use App\Models\Intern;
use App\Models\PermissionRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;


class DashboardAdminTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createAdmin();
    }

    public function test_admin_bisa_melihat_dashboard_dan_ringkasan_data_hitungannya_akurat(): void
    {
        $this->actingAs($this->admin, 'admins');

        Intern::query()->delete();
        Department::query()->delete();
        PermissionRequest::query()->delete();
        Absence::query()->delete();

        $deptIT = Department::create(['name' => 'IT', 'start_time' => '08:00', 'end_time' => '17:00']);
        $deptMkt = Department::create(['name' => 'Marketing', 'start_time' => '08:00', 'end_time' => '17:00']);

        $internSatu = $this->createIntern([
            'department_id' => $deptIT->id,
            'username' => 'intern.satu',
            'nin' => '3516102906240011',
            'phone' => '089681117111'
        ]);

        $internDua = $this->createIntern([
            'department_id' => $deptMkt->id,
            'username' => 'intern.dua',
            'nin' => '3516102906240022',
            'phone' => '089681117222'
        ]);

        // create permission request
        PermissionRequest::create(['intern_id' => $internSatu->id, 'start_date' => '2026-05-20', 'end_date' => '2026-05-20', 'type' => 'izin', 'reason' => 'Keperluan A', 'status' => 'pending']);
        PermissionRequest::create(['intern_id' => $internDua->id, 'start_date' => '2026-05-20', 'end_date' => '2026-05-20', 'type' => 'sakit', 'reason' => 'Keperluan B', 'status' => 'pending']);
        PermissionRequest::create(['intern_id' => $internSatu->id, 'start_date' => '2026-05-19', 'end_date' => '2026-05-19', 'type' => 'izin', 'reason' => 'Keperluan C', 'status' => 'approved']);

        // create absence
        $todayStr = Carbon::today()->toDateString();

        Absence::create(['intern_id' => $internSatu->id, 'date' => $todayStr, 'status' => 'hadir', 'validation_status' => 'disetujui']);
        Absence::create(['intern_id' => $internDua->id, 'date' => $todayStr, 'status' => 'terlambat', 'validation_status' => 'disetujui']);
        Absence::create(['intern_id' => $internSatu->id, 'date' => $todayStr, 'status' => 'alpha', 'validation_status' => 'disetujui']);

        $yesterdayStr = Carbon::yesterday()->toDateString();
        Absence::create(['intern_id' => $internSatu->id, 'date' => $yesterdayStr, 'status' => 'hadir', 'validation_status' => 'disetujui']);

        $response = $this->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('summary');

        $summary = $response->viewData('summary');

        $this->assertEquals(2, $summary['departments'], 'Total departemen tidak sesuai.');
        $this->assertEquals(2, $summary['permissions'], 'Total izin pending tidak sesuai.');
        $this->assertEquals(2, $summary['interns'], 'Total peserta magang tidak sesuai.');

        $this->assertEquals(1, $summary['attendance'], 'Hitungan absen hadir hari ini meleset.');
        $this->assertEquals(1, $summary['late'], 'Hitungan absen terlambat hari ini meleset.');
        $this->assertEquals(1, $summary['alpha'], 'Hitungan absen alpha hari ini meleset.');
    }
}
