<?php

namespace Tests;

use App\Models\Admin;
use App\Models\Department;
use App\Models\Intern;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function createDepartment(array $attributes = []): Department
    {
        $defaultAttributes = [
            'name' => 'IT',
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
        ];
        return Department::create(array_merge($defaultAttributes, $attributes));
    }

    protected function createIntern(array $attributes = []): Intern
    {
        if (!isset($attributes['department_id'])) {
            $department = $this->createDepartment();
            $attributes['department_id'] = $department->id;
        }

        $defaultAttributes = [
            'name' => 'Attius Andrew',
            'gender' => 'laki-laki',
            'address' => 'Jl. Contoh No. 123',
            'phone' => '089681117903',
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-31',
            'username' => 'attius',
            'password' => bcrypt('attius123'),
            'nin' => '3516102909040001',
        ];

        return Intern::create(array_merge($defaultAttributes, $attributes));
    }

    protected function createAdmin(array $attributes = []): Admin
    {
        $defaultAttributes = [
            'name'     => 'Jeremiah Isaiah',
            'username' => 'admin',
            'password' => bcrypt('admin123'),
        ];

        return Admin::create(array_merge($defaultAttributes, $attributes));
    }
}
