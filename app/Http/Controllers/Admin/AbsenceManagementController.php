<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;

class AbsenceManagementController extends Controller
{
    // show all absence
    public function index()
    {

        $absences = Absence::latest()->get();
        // dd($absence->toArray());

        return view('admin.absence', compact('absences'));
    }

    // update absence intern
    public function update(Request $request, $id) {}
}
