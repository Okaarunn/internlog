<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissionRequest extends Model
{
    use SoftDeletes, HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'intern_id',
        'approved_by',
        'start_date',
        'end_date',
        'type',
        'reason',
        'status',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function intern(): BelongsTo
    {
        return $this->belongsTo(Intern::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    public function absences()
    {
        return $this->intern->absences()
            ->whereBetween('date', [$this->start_date, $this->end_date]);
    }
}
