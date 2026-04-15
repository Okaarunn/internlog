<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Intern extends Authenticatable
{
    use SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'department_id',
        'nin',
        'name',
        'gender',
        'address',
        'phone',
        'start_date',
        'end_date',
        'username',
        'password',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class);
    }
}
