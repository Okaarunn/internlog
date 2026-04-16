<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Department;
use Illuminate\Http\Request;

use function Flasher\Noty\Prime\noty;

class AbsenceManagementController extends Controller
{
    // Show all absence with search, pagination, and filter
    public function index(Request $request)
    {
        $departments = Department::select('id', 'name')->get();

        $search = $request->input('search');
        $filterDept = $request->input('department_id');
        $filterDate = $request->input('date');

        $absences = Absence::with(['intern.department'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('intern', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('nin', 'like', "%{$search}%");
                });
            })
            ->when($filterDept, function ($query, $filterDept) {
                return $query->whereHas('intern', function ($q) use ($filterDept) {
                    $q->where('department_id', $filterDept);
                });
            })
            ->when($filterDate, function ($query, $filterDate) {
                return $query->whereDate('date', $filterDate);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.absence', compact('departments', 'absences'));
    }



    // update absence intern
    public function update(Request $request, $id)
    {

        // validation request
        $request->validate([
            'status' => 'required|in:hadir,terlambat,izin,sakit,alpha',
        ]);

        // get absence id
        $absence = Absence::findOrFail($id);

        // update abence
        $absence->update([
            'status' => $request->status,
            'admin_id' => auth('admins')->user()->id
        ]);

        // send notification
        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Data berhasil diupdate.');

        // redirect
        return redirect()->back();
    }
}
