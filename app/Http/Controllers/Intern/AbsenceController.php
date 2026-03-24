<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{
    public function index(Request $request)
    {
        $internId = Auth::guard('interns')->id();

        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        $absences = Absence::where('intern_id', $internId)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->paginate(5)->withQueryString();

        $todayAbsence = Absence::where('intern_id', $internId)
            ->whereDate('created_at', today())
            ->first();

        // summary berdasarkan bulan & tahun yang difilter
        $allAbsences = Absence::where('intern_id', $internId)
            ->whereMonth('created_at', $month)
            ->whereYear('created_at', $year)
            ->get();

        $summary = [
            'work_days' => $allAbsences->count(),
            'hadir'     => $allAbsences->whereIn('status', ['hadir', 'terlambat'])
                ->where('validation_status', '!=', 'ditolak')
                ->count(),
            'menunggu'  => $allAbsences->where('validation_status', 'menunggu')->count(),
            'alpha'     => $allAbsences->filter(function ($absence) {
                return $absence->status === 'alpha'
                    || $absence->validation_status === 'ditolak';
            })->count(),
        ];

        return view('intern.dashboard', compact('absences', 'todayAbsence', 'summary'));
    }

    // checkin
    public function checkin(Request $request)
    {
        $internId = Auth::guard('interns')->id();

        $alreadyCheckin = Absence::where('intern_id', $internId)
            ->whereDate('created_at', today())
            ->exists();

        if ($alreadyCheckin) {
            return redirect()->back()->with('error', 'Anda sudah check in hari ini');
        }

        // get departement
        $intern = Auth::guard('interns')->user();
        $startTime = Carbon::parse($intern->department->start_time);
        $now = now();

        if ($now->lessThan($startTime)) {
            $status = 'hadir';
        } else {
            $status = 'terlambat';
        }

        Absence::create([
            'intern_id'         => $internId,
            'check_in'          => now()->format('H:i:s'),
            'status'            => $status,
            'validation_status' => null,
        ]);

        return redirect()->back()->with('success', 'Check in berhasil');
    }

    // checkout
    public function checkout(Request $request)
    {
        $internId = Auth::guard('interns')->id();

        $todayAbsence = Absence::where('intern_id', $internId)
            ->whereDate('created_at', today())
            ->first();

        if (!$todayAbsence) {
            return redirect()->back()->with('error', 'Anda belum check in hari ini');
        }

        if ($todayAbsence->check_out !== null) {
            return redirect()->back()->with('error', 'Anda sudah check out hari ini');
        }

        // get departement
        $intern = Auth::guard('interns')->user();
        $end_time = Carbon::parse($intern->department->end_time);
        $checkOut = now();

        $checkIn  = Carbon::parse($todayAbsence->check_in);
        $duration = (int) $checkIn->diffInMinutes($checkOut);


        if ($checkOut->lessThan($end_time)) {
            $validation_status = 'menunggu';
        }

        $todayAbsence->update([
            'check_out' => $checkOut->format('H:i:s'),
            'duration'  => $duration,
            'notes_out' => $request->notes_out,
            'validation_status' => $validation_status
        ]);

        return redirect()->back()->with('success', 'Check out berhasil');
    }
}
