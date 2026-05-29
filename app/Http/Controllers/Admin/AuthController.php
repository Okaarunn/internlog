<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    // show login form
    public function showLoginForm()
    {

        return view('admin.login');
    }

    // Verify reCAPTCHA token
    private function verifyRecaptcha($token)
    {
        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $token,
            ]);

            $data = $response->json();

            \Log::info('reCAPTCHA Response:', $data);

            if (isset($data['success']) && $data['success']) {
                $score = $data['score'] ?? 0;
                \Log::info('reCAPTCHA Score: ' . $score);
                return $score > 0.5;
            }

            if (isset($data['error-codes'])) {
                \Log::error('reCAPTCHA Error Codes:', $data['error-codes']);
            }

            return false;
        } catch (\Exception $e) {
            \Log::error('reCAPTCHA Exception: ' . $e->getMessage());
            return false;
        }
    }

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'username' => 'required',
    //         'password' => 'required'
    //     ]);

    //     // KODE RENTAN: Menggunakan whereRaw agar input tanda petik (') bisa memanipulasi SQL
    //     $username = $request->username;
    //     $admin = Admin::whereRaw("username = '$username'")->first();

    //     // MODIFIKASI UJI COBA: Langsung loloskan login JIKA data admin ditemukan,
    //     // tanpa mengecek validasi Hash::check($request->password)
    //     if ($admin) {
    //         Auth::guard('admins')->login($admin);
    //         $request->session()->regenerate();

    //         noty()
    //             ->theme('sunset')
    //             ->closeWith(['click', 'button'])
    //             ->success('Selamat datang ' . $admin->name . '! Anda berhasil login.');
    //         return redirect()->route('admin.dashboard');
    //     }

    //     return redirect()->route('admin-login.show')->with('failed', 'Username atau password salah');
    // }

    // login
    public function login(Request $request)
    {
        // validation request
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'g-recaptcha-response' => 'required'
        ]);



        // Verify reCAPTCHA
        if (!$this->verifyRecaptcha($request->input('g-recaptcha-response'))) {
            return redirect()->route('admin-login.show')->with('failed', 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.');
        }

        // check if the admin is exists
        $admin = Admin::where('username', $request->username)->first();

        // check if user is exist and password is correct
        if ($admin && Hash::check($request->password, $admin->password)) {
            Auth::guard('admins')->login($admin);
            $request->session()->regenerate();

            noty()
                ->theme('sunset')
                ->closeWith(['click', 'button'])
                ->success('Selamat datang ' . $admin->name . '! Anda berhasil login.');
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin-login.show')->with('failed', 'Username atau password salah');
    }

    // logout
    public function logout(Request $request)
    {
        Auth::guard('admins')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Anda berhasil logout.');

        return redirect()->route('admin-login.show');
    }
}
