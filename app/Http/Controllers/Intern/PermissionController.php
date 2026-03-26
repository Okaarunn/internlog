<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'        => 'required|date',
            'type'        => 'required|in:izin,sakit',
            'description' => 'required|string',
        ]);

        // Cek apakah sudah ada absen di tanggal tersebut
        $alreadyAbsent = Absence::where('intern_id', Auth::guard('interns')->id())
            ->whereDate('created_at', $validated['date'])
            ->exists();

        if ($alreadyAbsent) {
            return redirect()->back()->with('error', 'Sudah ada data absensi di tanggal tersebut');
        }

        // Buat permission request
        $permission = PermissionRequest::create([
            'intern_id'  => Auth::guard('interns')->id(),
            'start_date' => $validated['date'],
            'end_date'   => $validated['date'],
            'type'       => $validated['type'],
            'reason'     => $validated['description'],
            'status'     => 'pending',
        ]);

        // Buat record absensi dengan status izin/sakit
        Absence::create([
            'intern_id'         => Auth::guard('interns')->id(),
            'date'              => $validated['date'],
            'status'            => $validated['type'],
            'validation_status' => 'menunggu',
            'notes_out'         => $validated['description'],
        ]);

        return redirect()->back()->with('success', 'Permintaan izin berhasil diajukan');
    }
}
