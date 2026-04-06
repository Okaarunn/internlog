<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Department;
use App\Models\Intern;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;

use Carbon\Carbon;


class DashboardAdminController extends Controller
{
    // get summary data
    public function index()
    {
        // get total data
        $departments = Department::count();
        $permissions = PermissionRequest::where('status', 'pending')->count();
        $interns = Intern::count();

        $attendanceAbsences = Absence::where('status', 'hadir')
            ->whereDate('date', Carbon::today())
            ->count();

        $lateAbsences = Absence::where('status', 'terlambat')
            ->whereDate('date', Carbon::today())
            ->count();

        $alphaAbsences = Absence::where('status', 'alpha')
            ->whereDate('date', Carbon::today())
            ->count();

        // summary data
        $summary = [
            'departments' => $departments,
            'permissions' => $permissions,
            'interns' => $interns,
            'attendance' => $attendanceAbsences,
            'late' =>  $lateAbsences,
            'alpha' => $alphaAbsences
        ];

        return view('admin.dashboard', compact('summary'));
    }
}
