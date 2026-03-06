<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // show login form
    public function showLoginForm()
    {
        return view('intern.login');
    }

    // login
    public function login(Request $request)
    {
        // validate request
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // find user by username
        $intern = Intern::where('username', $request->username)->first();

        // check if user exists and password is correct
        if ($intern && Hash::check($request->password, $intern->password)) {
            Auth::guard('interns')->login($intern);
            $request->session()->regenerate();

            noty()
                ->theme('sunset')
                ->closeWith(['click', 'button'])
                ->success('Selamat datang ' . $intern->name . '! Anda berhasil login.');
            return redirect()->route('dashboard');
        }

        return redirect()->route('login.show')->with('failed', 'Username atau password salah');
    }

    // logout
    public function logout()
    {
        // invalidate session
        Auth::guard('interns')->logout();
        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Your account has been restored.');


        return redirect()->route('login.show');
    }
}
