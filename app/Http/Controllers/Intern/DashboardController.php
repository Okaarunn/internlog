<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\PermissionRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $internId = Auth::guard('interns')->id();
        $intern = Auth::guard('interns')->user();

        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);

        // get data absences for current month
        $absences = Absence::where('intern_id', $internId)
            ->whereMonth('date', $month)
            ->whereYear('date', $year)
            ->orderBy('date', 'desc')
            ->paginate(10)->withQueryString();

        // check today absence
        $todayAbsence = Absence::where('intern_id', $internId)
            ->whereDate('date', today())
            ->first();

        // total attendance summary
        $allAbsences = Absence::where('intern_id', $internId)
            ->get();

        // initial total work days
        $startDate = Carbon::parse($intern->start_date);
        $endDate   = Carbon::parse($intern->end_date);
        $workDays  = 0;

        // get work days
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            if (!$date->isSunday()) {
                $workDays++;
            }
        }

        // intern summary data
        $summary = [
            'work_days' => $workDays,
            'hadir'     => $allAbsences->whereIn('status', ['hadir', 'terlambat'])
                ->count(),
            'menunggu'  => $allAbsences->where('validation_status', 'menunggu')->count(),
            'alpha'  => $allAbsences->where('status', 'alpha')->count(),
        ];

        $permissions = PermissionRequest::where('intern_id', $internId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);


        // return data to view
        return view('intern.dashboard', compact('absences', 'todayAbsence', 'summary', 'permissions'));
    }
}
