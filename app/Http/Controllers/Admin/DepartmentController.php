<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class DepartmentController extends Controller
{
    public function index()
    {
        // get all department
        $departments = Department::withCount('interns')->get();

        // summary deartment data for dashboard
        return view('admin.department', compact('departments'));
    }

    public function store(Request $request)
    {
        // check name is exist
        if (Department::where('name', $request->name)->exists()) {
            noty()->theme('sunset')->error('Gagal! Nama departemen sudah terdaftar.');
            return redirect()->back()->withInput();
        }

        $request->validate([
            'name' => 'required',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        Department::create($request->all());

        noty()->theme('sunset')->success('Data berhasil ditambahkan.');
        return redirect()->back();
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:100', Rule::unique('departments')->ignore($id)],
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            if ($validator->errors()->has('name')) {
                noty()
                    ->theme('sunset')
                    ->error('Gagal update! Nama departemen sudah digunakan oleh data lain.');
            }

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $department->update([
            'name' => $request->name,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        noty()
            ->theme('sunset')
            ->success('Data berhasil diupdate.');

        return redirect()->back();
    }

    public function destroy($id)
    {

        // get department with intern qty
        $department = Department::withCount('interns')->findOrFail($id);


        // check if there are apprentices
        if ($department->interns_count > 0) {

            noty()
                ->theme('sunset')
                ->closeWith(['click', 'button'])
                ->error('Departemen tidak dapat dihapus karena masih ada peserta.');

            return redirect()->back();
        }

        // if not, delete
        $department->delete();

        noty()
            ->theme('sunset')
            ->closeWith(['click', 'button'])
            ->success('Departemen berhasil dihapus');

        return redirect()->back();
    }
}
