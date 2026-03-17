<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Absence extends Model
{

    use HasFactory, Notifiable, SoftDeletes, HasUuids;

    protected $primaryKey = 'id_absence';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_absence',
        'id_admin',
        'id_intern',
        'date_absence',
        'status',
        'check_in',
        'check_out',
        'duration',
        'validation_status',
        'information',
    ];

    public function uniqueIds(): array
    {
        return ['id_absence'];
    }

    // one to many relationship with intern table
    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class, 'id_intern', 'id_intern');
    }

    // one to many relationship with admin table
    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'id_admin', 'id_admin');
    }
}
