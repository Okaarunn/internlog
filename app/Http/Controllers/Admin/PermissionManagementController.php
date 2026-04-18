<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\PermissionRequest;
use Illuminate\Http\Request;

class PermissionManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = PermissionRequest::with(['intern.department', 'approvedBy']);
        
        // Filter berdasarkan pencarian nama
        if ($request->filled('search')) {
            $query->whereHas('intern', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        $permissions = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
        
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
