<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AbsenceController extends Controller
{
    public function index($id)
    {
        $absences = Absence::where('', Auth::guard('interns')->id())->get();
        return view('intern.dashboard', compact('absences'));
    }


    // checkin
    public function checkin(Request $request)
    {

        $internId = Auth::guard('interns')->id();

        $alreadyCheckin = Absence::where('id_intern', $internId)
            ->whereDate('created_at', today())
            ->exists();

        if ($alreadyCheckin) {
            return redirect()->back()->with('error', 'Anda sudah check in hari ini');
        }

        $hour = (int) date('H');
        if ($hour < 8) {
            $status = 'hadir';
            $validation_status = 'approved';
        } else {
            $status = 'terlambat';
            $validation_status = 'pending';
        }

        Absence::create([
            'id_intern' => $internId,
            'date_absence' => today(),
            'status' => $status,
            'check_in' => now(),
            'validation_status' => $validation_status,
            'information' => $request->information ?? null
        ]);

        return redirect()->back()->with('success', 'Check in berhasil');
    }

    // checkout
    public function checkout(Request $request)
    {
        $internId = Auth::guard('interns')->id();

        $todayAbsence = Absence::where('id_intern', $internId)
            ->whereDate('created_at', today())
            ->first();

        if (!$todayAbsence) {
            return redirect()->back()->with('error', 'Anda belum check in');
        }

        $alreadyCheckout = $todayAbsence->check_out !== null;
        if ($alreadyCheckout) {
            return redirect()->back()->with('error', 'Anda sudah check out hari ini');
        }

        $todayAbsence->update([
            'check_out' => now(),
            'duration' => $todayAbsence->check_in->diffInHours(now())
        ]);

        return redirect()->back()->with('success', 'Check out berhasil');
    }
}
