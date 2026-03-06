<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class InternController extends Controller
{
    // get data interns with department
    public function store(Request $request)
    {

        // validate request
        $request->validate([
            'id_department' => 'required|exists:departments,id_department',
            'nin' => 'required|unique:interns,nin|max:16',
            'name' => 'required|max:100',
            'gender' => 'required|in:laki-laki,perempuan',
            'address' => 'required|max:255',
            'phone' => 'required|unique:interns,phone|max:12',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'username' => 'required|unique:interns,username|max:100',
            'password' => 'required|min:6',
        ]);

        $request['password'] = Hash::make($request->password);
        $intern = Intern::create($request->all());

        // return response json with status code 201
        return response(['intern' => $intern, 'message' => 'Intern created successfully'], 201);
    }
}
