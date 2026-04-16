<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;

use function Symfony\Component\Clock\now;

class PermissionManagementController extends Controller
{
    public function index()
    {
        $permissions = PermissionRequest::all();
        return view('admin.permission', compact('permissions'));
    }

    // update status permission

    public function update(Request $request, $id)
    {
        $permission = PermissionRequest::findOrFail($id);


        // validation
        $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        $permission->update([
            'status' => $request->status,
            'approved_by' => auth('admins')->user()->id,
            'approved_at' => now()
        ]);

        // update absence validation_status
        $validationStatus = $request->status === 'approved' ? 'disetujui' : 'ditolak';

        $updated_by = auth('admins')->user()->id;

        Absence::where('intern_id', $permission->intern_id)
            ->whereBetween('date', [$permission->start_date, $permission->end_date])
            ->update(['validation_status' => $validationStatus, 'admin_id' => $updated_by]);

        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Data perizinan berhasil diupdate.');

        return redirect()->back();
    }
}
