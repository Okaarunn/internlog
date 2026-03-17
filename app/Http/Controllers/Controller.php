<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    protected function authIntern(): Intern
    {
        return Auth::guard('interns')->user();
    }

    // get intern id
    protected function authInternId(): string
    {
        return Auth::guard('interns')->user()->id_intern;
    }
}
