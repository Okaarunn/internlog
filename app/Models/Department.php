<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes, HasUuids;

    protected $primaryKey = 'id_department';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_department',
        'name',
        'start_time',
        'end_time',
    ];

    public function uniqueIds(): array
    {
        return ['id_department'];
    }

    public function interns(): HasMany
    {
        return $this->hasMany(Intern::class, 'id_department', 'id_department');
    }
}
