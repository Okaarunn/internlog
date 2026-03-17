<?php

namespace App\Http\Controllers;

use App\Models\Department;

class DepartmentController extends Controller
{
    // get all data department
    public function index()
    {
        $departments = Department::all();

        // return data department to department view
        // return view('departments', ['departments' => $departments]);
    }
}
