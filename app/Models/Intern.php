<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Intern extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasUuids;

    protected $primaryKey = 'id_intern';
    public $incrementing = false;
    protected $keyType = 'string';


    // fillable fields
    protected $fillable = [
        'id_intern',
        'id_department',
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

    protected $hidden = [
        'password',
    ];

    public function uniqueIds(): array
    {
        return ['id_intern'];
    }

    // one to one relationship with department
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'id_department', 'id_department');
    }

    // one to many relationship with absence
    public function absences(): HasMany
    {
        return $this->hasMany(Absence::class, 'id_intern', 'id_intern');
    }
}
