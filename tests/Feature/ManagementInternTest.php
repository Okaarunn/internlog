<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Intern;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManagementInternTest extends TestCase
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

    public function test_admin_bisa_melihat_halaman_manajemen_peserta_tanpa_filter(): void
    {
        $this->actingAs($this->admin, 'admins');

        $response = $this->get(route('admin.intern'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.intern');
        $response->assertViewHasAll(['departments', 'interns']);

        $internsInView = $response->original->getData()['interns'];
        $this->assertGreaterThanOrEqual(1, $internsInView->count());
    }

    public function test_admin_bisa_mencari_peserta_berdasarkan_nama_atau_username(): void
    {
        $this->actingAs($this->admin, 'admins');


        $internSpesifik = $this->createIntern([
            'name' => 'Rachelcia Wijaya',
            'username' => 'rachel',
            'nin' => '3516102906240002',
            'phone' => '089681117902'
        ]);


        // search with username rachel
        $response = $this->get(route('admin.intern', ['search' => 'rachel']));

        $internsInView = $response->original->getData()['interns'];

        // Harus terfilter dengan tepat
        $this->assertCount(1, $internsInView);
        $this->assertEquals('Rachelcia Wijaya', $internsInView->first()->name);
    }

    public function test_admin_bisa_memfilter_peserta_berdasarkan_departemen(): void
    {
        $this->actingAs($this->admin, 'admins');

        // create department
        $deptIT = Department::create(['name' => 'IT', 'start_time' => '08:00', 'end_time' => '17:00']);

        // create new intern
        $internIT = $this->createIntern([
            'department_id' => $deptIT->id,
            'name' => 'Aditya Wijaya',
            'username' => 'aditya',
            'nin' => '3516102906240001',
            'phone' => '089681227903'
        ]);

        $response = $this->get(route('admin.intern', ['department_id' => $deptIT->id]));

        $internsInView = $response->original->getData()['interns'];

        $this->assertCount(1, $internsInView);
        $this->assertEquals($internIT->id, $internsInView->first()->id);
    }



    public function test_admin_berhasil_menambahkan_peserta_magang_baru(): void
    {
        $this->actingAs($this->admin, 'admins');

        $department = Department::first() ?? $this->createDepartment();

        $response = $this->post(route('admin.intern.store'), [
            'department_id' => $department->id,
            'nin'           => '1234567890123456',
            'name'          => 'Gatot Kaca',
            'gender'        => 'laki-laki',
            'address'       => 'Jl. Gatot Subroto No. 10',
            'phone'         => '081299998888',
            'start_date'    => '2026-06-01',
            'end_date'      => '2026-08-31',
            'username'      => 'gatotkaca',
            'password'      => 'secret123',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('interns', [
            'username' => 'gatotkaca',
            'name' => 'Gatot Kaca'
        ]);


        $intern = Intern::where('username', 'gatotkaca')->first();
        $this->assertTrue(Hash::check('secret123', $intern->password));
    }

    public function test_tambah_peserta_gagal_jika_username_sudah_digunakan(): void
    {
        $this->actingAs($this->admin, 'admins');

        $department = Department::first() ?? $this->createDepartment();

        $response = $this->post(route('admin.intern.store'), [
            'department_id' => $department->id,
            'nin'           => '9994567890123456',
            'name'          => 'Attius Clone',
            'gender'        => 'laki-laki',
            'address'       => 'Jl. Alamat Lain',
            'phone'         => '081233334444',
            'start_date'    => '2026-06-01',
            'end_date'      => '2026-08-31',
            'username'      => $this->intern->username,
            'password'      => 'password123',
        ]);

        $response->assertSessionHasErrors(['username']);
    }



    public function test_admin_berhasil_mengupdate_profil_peserta_tanpa_ganti_password(): void
    {
        $this->actingAs($this->admin, 'admins');

        $oldPassword = $this->intern->password;

        $response = $this->put(route('admin.intern.update', $this->intern->id), [
            'department_id' => $this->intern->department_id,
            'nin'           => $this->intern->nin,
            'name'          => 'Attius Andrew Updated',
            'gender'        => 'laki-laki',
            'address'       => 'Jl. Baru Saja Pindah No. 5',
            'phone'         => $this->intern->phone,
            'start_date'    => '2026-05-01',
            'end_date'      => '2026-05-31',
            'username'      => $this->intern->username,
            'password'      => '',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('interns', [
            'id' => $this->intern->id,
            'name' => 'Attius Andrew Updated',
            'address' => 'Jl. Baru Saja Pindah No. 5'
        ]);

        $this->assertEquals($oldPassword, $this->intern->fresh()->password);
    }



    public function test_admin_berhasil_menghapus_permanen_peserta_magang(): void
    {
        $this->actingAs($this->admin, 'admins');

        $internTarget = $this->createIntern([
            'name' => 'Peserta Dihapus',
            'username' => 'pesertahapus',
            'nin' => '3516102906240099',
            'phone' => '089681117999'
        ]);

        // Kirim request delete
        $response = $this->from(route('admin.intern'))
            ->delete(route('admin.intern.destroy', $internTarget->id));

        $response->assertRedirect(route('admin.intern'));

        $this->assertDatabaseMissing('interns', [
            'id' => $internTarget->id
        ]);
    }
}
