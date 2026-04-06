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
            'start_date'  => 'required|date',
            'end_date'    => 'required|date|after_or_equal:start_date',
            'type'        => 'required|in:izin,sakit',
            'description' => 'required|string',
        ]);

        $internId = Auth::guard('interns')->id();

        // Cek apakah sudah ada absen di salah satu tanggal tersebut
        $alreadyAbsent = Absence::where('intern_id', $internId)
            ->whereBetween('date', [$validated['start_date'], $validated['end_date']])
            ->exists();

        if ($alreadyAbsent) {
            return redirect()->back()->with('error', 'Sudah ada data absensi di salah satu tanggal tersebut');
        }

        // Buat permission request
        PermissionRequest::create([
            'intern_id'  => $internId,
            'start_date' => $validated['start_date'],
            'end_date'   => $validated['end_date'],
            'type'       => $validated['type'],
            'reason'     => $validated['description'],
            'status'     => 'pending',
        ]);

        // Buat record absensi untuk setiap hari (kecuali Minggu)
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end   = \Carbon\Carbon::parse($validated['end_date']);

        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isSunday()) continue;

            Absence::create([
                'intern_id'         => $internId,
                'date'              => $date->format('Y-m-d'),
                'status'            => $validated['type'],
                'validation_status' => 'menunggu',
                'notes_out'         => $validated['description'],
            ]);
        }

        return redirect()->back()->with('success', 'Permintaan izin berhasil diajukan');
    }
}
