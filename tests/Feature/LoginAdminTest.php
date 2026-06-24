<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LoginAdminTest extends TestCase
{
    use RefreshDatabase;

    private $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = $this->createAdmin();

        // Mock reCAPTCHA response for testing
        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
                'score' => 0.9
            ])
        ]);
    }


    public function test_halaman_login_admin_bisa_diakses(): void
    {
        $response = $this->get(route('admin-login.show'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.login');
    }



    public function test_login_admin_berhasil_dengan_kredensial_valid(): void
    {
        $response = $this->post(route('admin-login.submit'), [
            'username' => 'yeremia',
            'password' => 'adminyeremia',
            'g-recaptcha-response' => 'dummy-recaptcha-token'
        ]);

        $response->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($this->admin, 'admins');
    }

    public function test_login_admin_gagal_jika_password_salah(): void
    {
        $response = $this->post(route('admin-login.submit'), [
            'username' => 'yeremia',
            'password' => 'password_salah',
            'g-recaptcha-response' => 'dummy-recaptcha-token'
        ]);

        $response->assertRedirect(route('admin-login.show'));

        $response->assertSessionHas('failed', 'Username atau password salah');

        $this->assertGuest('admins');
    }

    public function test_login_admin_gagal_jika_username_tidak_ditemukan(): void
    {
        $response = $this->post(route('admin-login.submit'), [
            'username' => 'username_ngaco',
            'password' => 'admin123',
            'g-recaptcha-response' => 'dummy-recaptcha-token'
        ]);

        $response->assertRedirect(route('admin-login.show'));
        $response->assertSessionHas('failed', 'Username atau password salah');
        $this->assertGuest('admins');
    }

    public function test_login_admin_gagal_jika_inputan_kosong(): void
    {
        $response = $this->post(route('admin-login.submit'), [
            'username' => '',
            'password' => '',
            'g-recaptcha-response' => ''
        ]);

        $response->assertSessionHasErrors(['username', 'password', 'g-recaptcha-response']);
        $this->assertGuest('admins');
    }



    public function test_logout_admin_berhasil(): void
    {
        $this->actingAs($this->admin, 'admins');

        $response = $this->post(route('admin.logout'));

        $response->assertRedirect(route('admin-login.show'));

        $this->assertGuest('admins');
    }
}
