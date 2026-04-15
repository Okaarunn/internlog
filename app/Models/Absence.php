<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Absence extends Model
{
    use SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'intern_id',
        'admin_id',
        'date',
        'check_in',
        'check_out',
        'duration',
        'notes_out',
        'status',
        'validation_status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }
}
