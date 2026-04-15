<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Intern;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class InternController extends Controller
{

    // get intern with search, pagination, filter data
    public function index(Request $request)
    {
        $departments = Department::select('id', 'name')->get();

        $search = $request->input('search');
        $filterDept = $request->input('department_id'); // Ambil input filter

        $interns = Intern::with('department')
            // Filter Pencarian
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('username', 'like', "%{$search}%")
                        ->orWhere('nin', 'like', "%{$search}%");
                });
            })
            // Filter Departemen
            ->when($filterDept, function ($query, $filterDept) {
                return $query->where('department_id', $filterDept);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.intern', compact('departments', 'interns'));
    }

    // get data interns with department
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'nin'           => 'required|unique:interns,nin|max:16',
            'name'          => 'required|max:100',
            'gender'        => 'required|in:laki-laki,perempuan',
            'address'       => 'required|max:255',
            'phone'         => 'required|unique:interns,phone|max:12',
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'username'      => 'required|unique:interns,username|max:100',
            'password'      => 'required|min:6',
        ]);

        if ($validator->fails()) {
            $firstError = $validator->errors()->first();
            noty()
                ->theme('sunset')
                ->error('Gagal simpan! ' . $firstError);
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Intern::create([
                'department_id' => $request->department_id,
                'nin'           => $request->nin,
                'name'          => $request->name,
                'gender'        => $request->gender,
                'address'       => $request->address,
                'phone'         => $request->phone,
                'start_date'    => $request->start_date,
                'end_date'      => $request->end_date,
                'username'      => $request->username,
                'password'      => Hash::make($request->password),
            ]);

            noty()->theme('sunset')->success('Peserta magang berhasil ditambahkan.');
            return redirect()->back();
        } catch (\Exception $e) {
            noty()->theme('sunset')->error('Terjadi kesalahan sistem.');
            return redirect()->back()->withInput();
        }
    }

    // update data intern
    public function update(Request $request, $id)
    {
        // get user id
        $intern = Intern::findOrFail($id);
        // validate user id and ignore user unique value
        $validator = Validator::make($request->all(), [
            'department_id' => 'required',
            'nin'           => 'required|max:16|unique:interns,nin,' . $id,
            'name'          => 'required|max:100',
            'gender'        => 'required|in:laki-laki,perempuan',
            'address'       => 'required|max:255',
            'phone'         => 'required|max:12|unique:interns,phone,' . $id,
            'start_date'    => 'required|date',
            'end_date'      => 'required|date|after:start_date',
            'username'      => 'required|max:100|unique:interns,username,' . $id,
            'password'      => 'nullable|min:6',
        ]);

        // handle error
        if ($validator->fails()) {
            noty()
                ->theme('sunset')
                ->error('Update gagal! ' . $validator->errors()->first());

            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $data = $request->all();

            // handle password not null
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            } else {
                unset($data['password']);
            }

            // update data user
            $intern->update($data);

            noty()->theme('sunset')->success('Data ' . $intern->name . ' berhasil diperbarui');
            return redirect()->back();
        } catch (\Exception $e) {
            // handle error
            noty()
                ->theme('sunset')
                ->error('Terjadi kesalahan sistem saat memperbarui data.');

            return redirect()->back()->withInput();
        }
    }

    // delete data intern
    public function destroy($id)
    {
        $intern = Intern::findOrFail($id);

        try {
            // hapus relasi dulu
            $intern->absences()->delete();

            // hapus permanen
            $intern->forceDelete();

            noty()->theme('sunset')->success('Peserta magang dan data absensinya berhasil dihapus');
            return redirect()->back();
        } catch (\Exception $e) {
            noty()->theme('sunset')->error('Gagal menghapus data: ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
