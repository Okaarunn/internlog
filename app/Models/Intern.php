<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Intern extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'id_intern';
    public $incrementing = false;
    protected $keyType = 'string';


    // fillable fields
    protected $fillable = [
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
}
