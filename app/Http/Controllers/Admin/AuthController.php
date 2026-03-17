<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // show login form
    public function showLoginForm()
    {

        return view('admin.login');
    }

    // login
    public function login(Request $request)
    {
        // validation request
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

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
    public function logout()
    {
        Auth::guard('admins')->logout();
        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Anda berhasil logout.');


        return redirect()->route('admin-login.show');
    }
}
