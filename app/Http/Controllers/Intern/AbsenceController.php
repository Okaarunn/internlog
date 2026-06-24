<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AbsenceController extends Controller
{

    // checkin
    public function checkin(Request $request)
    {

        $internId = Auth::guard('interns')->id();
        $intern = Auth::guard('interns')->user();

        // Check if internship has ended
        if (today()->greaterThan(Carbon::parse($intern->end_date))) {
            return redirect()->back()->with('error', 'Masa magang Anda sudah berakhir');
        }

        $alreadyCheckin = Absence::where('intern_id', $internId)
            ->whereDate('date', today())
            ->exists();

        if ($alreadyCheckin) {
            return redirect()->back()->with('error', 'Anda sudah check in hari ini');
        }

        // get departement
        $startTime = Carbon::parse($intern->department->start_time);
        $now = now();


        // validation and status
        if ($now->lessThan($startTime)) {
            $status = 'hadir';
            $validation_status = "disetujui";
        } else {
            $status = 'terlambat';
            $validation_status = "menunggu";
        }

        // create data
        Absence::create([
            'intern_id'         => $internId,
            'date' => today(),
            'check_in'          => now()->format('H:i:s'),
            'status'            => $status,
            'validation_status' => $validation_status,
        ]);


        // notification
        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Check in berhasil.');

        return redirect()->back();
    }

    // checkout
    public function checkout(Request $request)
    {
        $internId = Auth::guard('interns')->id();
        $intern = Auth::guard('interns')->user();

        // Check if internship has ended
        if (today()->greaterThan(Carbon::parse($intern->end_date))) {
            noty()
                ->theme('sunset')
                ->closeWith(['click', 'button'])
                ->error('Masa magang Anda sudah berakhir.');
            return redirect()->back();
        }

        $todayAbsence = Absence::where('intern_id', $internId)
            ->whereDate('date', today())
            ->first();

        if (!$todayAbsence) {
            noty()
                ->theme('sunset')
                ->closeWith(['click', 'button'])
                ->error('Anda belum check in hari ini.');
            return redirect()->back();
        }

        if ($todayAbsence->check_out !== null) {
            noty()
                ->theme('sunset')
                ->closeWith(['click', 'button'])
                ->error('Anda sudah check out hari ini.');
            return redirect()->back();
        }
        if ($todayAbsence->status === 'alpha') {
            noty()
                ->theme('sunset')
                ->closeWith(['click', 'button'])
                ->error('Anda tercatat alpha hari ini.');
            return redirect()->back();
        }

        // get departement
        $end_time = Carbon::parse($intern->department->end_time);
        $checkOut = now();

        $checkIn  = Carbon::parse($todayAbsence->check_in);
        $duration = (int) $checkIn->diffInMinutes($checkOut);

        $validation_status = $todayAbsence->validation_status;

        if ($todayAbsence->validation_status === 'disetujui' && $checkOut->lessThan($end_time)) {
            $validation_status = 'menunggu';
        }

        $notes_out = null;
        if ($checkOut->lessThan($end_time)) {
            if (empty($request->notes_out)) {
                noty()
                    ->theme('sunset')
                    ->closeWith(['click', 'button'])
                    ->error('Catatan keluar tidak boleh kosong ketika checkout sebelum jam pulang.');
                return redirect()->back();
            }
            $notes_out = $request->notes_out;
        }

        $todayAbsence->update([
            'check_out' => $checkOut->format('H:i:s'),
            'duration'  => $duration,
            'notes_out' => $notes_out,
            'validation_status' => $validation_status
        ]);

        // notification
        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Check out berhasil.');

        return redirect()->back();
    }
}
