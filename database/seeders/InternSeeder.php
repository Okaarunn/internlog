<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Intern;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InternSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $department = Department::where('name', 'IT')->first();

        Intern::create([
            'department_id' => $department->id,
            'nin' => '3516102906240001',
            'name' => 'Aditya Wijaya',
            'gender' => 'laki-laki',
            'address' => 'Desa Ngrowo Dusun Pendowo RT.23/RW.02 Kecamatan Bangsal Kabupaten Mojokerto',
            'phone' => '089681117903',
            'start_date' => '2026-02-03',
            'end_date' => '2026-05-31',
            'username' => 'aditya',
            'password' => Hash::make('aditya123')
        ]);
    }
}
