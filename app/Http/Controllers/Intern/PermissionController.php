<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string',
        ]);

        PermissionRequest::create([
            'intern_id' => Auth::guard('interns')->id(),
            'start_date' => $validated['date'],
            'end_date' => $validated['date'],
            'type' => 'izin',
            'reason' => $validated['description'],
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Permintaan izin berhasil diajukan');
    }
}
