<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function watchLogin()
    {
        return view('intern.login');
    }


    public function submitLogin(Request $request)
    {
        // validate request
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $data = Intern::where('username', $request->username)->first();

        if ($data && Hash::check($request->password, $data->password)) {
            Auth::guard('interns')->login($data);
            $request->session()->regenerate();
            return redirect()->route('dashboard')->with('success', 'Login berhasil');
        }

        return redirect()->route('login.watch')->with('failed', 'Username atau password salah');
    }

    // logout
    public function logout()
    {
        Auth::guard('interns')->logout();
        return redirect()->route('login.watch')->with('success', 'Logout berhasil');
    }
}
