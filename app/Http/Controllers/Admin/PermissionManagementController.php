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

        if ($request->status === 'rejected') {
            // jika ditolak: hapus semua absence records dari permission ini
            Absence::where('permission_request_id', $permission->id)->delete();
        } else if ($request->status === 'approved') {
            // jika diterima: update validation_status semua absence terkait
            Absence::where('permission_request_id', $permission->id)
                ->update([
                    'validation_status' => 'disetujui',
                    'admin_id' => auth('admins')->user()->id
                ]);
        }

        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Data perizinan berhasil diupdate.');

        return redirect()->back();
    }
}
