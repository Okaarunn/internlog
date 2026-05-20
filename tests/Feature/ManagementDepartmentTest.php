<?php

namespace Tests\Feature;

use App\Models\Department;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManagementDepartmentTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createAdmin();
    }



    public function test_admin_bisa_melihat_daftar_departemen_dan_summary(): void
    {
        $this->actingAs($this->admin, 'admins');

        Department::query()->delete();

        $deptIT = Department::create(['name' => 'IT', 'start_time' => '08:00', 'end_time' => '17:00']);
        Department::create(['name' => 'Finance', 'start_time' => '08:30', 'end_time' => '16:30']);

        $this->createIntern([
            'department_id' => $deptIT->id
        ]);

        $response = $this->get(route('admin.department'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.department');
        $response->assertViewHasAll(['departments', 'totalDepartments', 'totalInterns']);

        $this->assertEquals(2, $response->original->getData()['totalDepartments']);
        $this->assertEquals(1, $response->original->getData()['totalInterns']);
    }



    public function test_admin_berhasil_menambahkan_departemen_baru(): void
    {
        $this->actingAs($this->admin, 'admins');

        $response = $this->post(route('admin.department.store'), [
            'name' => 'Human Resources',
            'start_time' => '08:00',
            'end_time' => '17:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('departments', [
            'name' => 'Human Resources',
            'start_time' => '08:00',
            'end_time' => '17:00',
        ]);
    }

    public function test_tambah_departemen_gagal_jika_nama_sudah_terdaftar(): void
    {
        $this->actingAs($this->admin, 'admins');

        Department::create(['name' => 'IT', 'start_time' => '08:00', 'end_time' => '17:00']);

        $response = $this->post(route('admin.department.store'), [
            'name' => 'IT',
            'start_time' => '09:00',
            'end_time' => '18:00',
        ]);

        $response->assertRedirect();
        $this->assertEquals(1, Department::where('name', 'IT')->count());
    }

    public function test_tambah_departemen_gagal_jika_jam_selesai_mendahului_jam_mulai(): void
    {
        $this->actingAs($this->admin, 'admins');

        $response = $this->post(route('admin.department.store'), [
            'name' => 'Marketing',
            'start_time' => '17:00',
            'end_time' => '08:00',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseMissing('departments', ['name' => 'Marketing']);
    }



    public function test_admin_berhasil_mengubah_data_departemen(): void
    {
        $this->actingAs($this->admin, 'admins');

        $dept = Department::create(['name' => 'R & D', 'start_time' => '08:00', 'end_time' => '17:00']);

        $response = $this->put(route('admin.department.update', $dept->id), [
            'name' => 'Research and Design',
            'start_time' => '08:30',
            'end_time' => '17:30',
        ]);

        $response->assertRedirect(route('admin.department'));
        $this->assertDatabaseHas('departments', [
            'id' => $dept->id,
            'name' => 'Research and Design',
            'start_time' => '08:30',
            'end_time' => '17:30',
        ]);
    }

    public function test_update_departemen_gagal_jika_jam_selesai_ngaco(): void
    {
        $this->actingAs($this->admin, 'admins');

        $dept = Department::create(['name' => 'QA', 'start_time' => '08:00', 'end_time' => '17:00']);

        $response = $this->put(route('admin.department.update', $dept->id), [
            'name' => 'QA',
            'start_time' => '16:00',
            'end_time' => '15:00',
        ]);

        $response->assertRedirect();
        $this->assertEquals('17:00:00', $dept->fresh()->end_time);
    }



    public function test_admin_bisa_menghapus_departemen_kosong(): void
    {
        $this->actingAs($this->admin, 'admins');

        $uniqueDeptName = 'Creative Studio Dept ' . rand(100, 999);

        $dept = Department::create([
            'name' => $uniqueDeptName,
            'start_time' => '08:00',
            'end_time' => '17:00'
        ]);

        $response = $this->from(route('admin.department'))
            ->delete(route('admin.department.destroy', $dept->id));

        $response->assertRedirect(route('admin.department'));


        $this->assertSoftDeleted('departments', [
            'name' => $uniqueDeptName
        ]);
    }
    public function test_hapus_departemen_gagal_jika_masih_ada_peserta_magang(): void
    {
        $this->actingAs($this->admin, 'admins');

        $intern = $this->createIntern();
        $deptId = $intern->department_id;

        $response = $this->delete(route('admin.department.destroy', $deptId));

        $response->assertRedirect();
        $this->assertDatabaseHas('departments', ['id' => $deptId]);
    }
}
