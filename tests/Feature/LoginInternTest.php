<?php

namespace Tests\Feature;

use App\Models\Intern;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginInternTest extends TestCase
{
    use RefreshDatabase;

    private Intern $intern;

    private string $password = 'attius123';

    protected function setUp(): void
    {
        parent::setUp();
        $this->intern = $this->createIntern();
    }


    public function test_intern_dapat_login_dengan_kredensial_valid(): void
    {
        $this->postLogin($this->intern->username, $this->password)
            ->assertRedirect(route('intern.dashboard'));

        $this->assertAuthenticatedAs($this->intern, 'interns');
    }

    public function test_intern_tidak_dapat_login_dengan_password_salah(): void
    {
        $this->postLogin($this->intern->username, 'password-salah')
            ->assertRedirect(route('login.show'))
            ->assertSessionHas('failed', 'Username atau password salah');

        $this->assertGuest('interns');
    }

    public function test_intern_tidak_dapat_login_dengan_username_tidak_terdaftar(): void
    {
        $this->postLogin('username-tidak-ada', $this->password)
            ->assertRedirect(route('login.show'))
            ->assertSessionHas('failed', 'Username atau password salah');

        $this->assertGuest('interns');
    }



    public function test_login_membutuhkan_username(): void
    {
        $this->postLogin('', $this->password)
            ->assertSessionHasErrors(['username']);

        $this->assertGuest('interns');
    }

    public function test_login_membutuhkan_password(): void
    {
        $this->postLogin($this->intern->username, '')
            ->assertSessionHasErrors(['password']);

        $this->assertGuest('interns');
    }



    public function test_intern_dapat_logout(): void
    {
        $this->actingAs($this->intern, 'interns')
            ->post('/logout')
            ->assertRedirect(route('login.show'));

        $this->assertGuest('interns');
    }



    private function postLogin(string $username, string $password): \Illuminate\Testing\TestResponse
    {
        return $this->post('/login', compact('username', 'password'));
    }
}
