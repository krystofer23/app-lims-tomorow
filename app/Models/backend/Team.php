<?php

namespace App\Models\backend;

use App\Casts\LocalTimezone;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    protected $connection = 'backend_greenlab';

    use HasFactory, SoftDeletes;

    protected $table = 'teams';

    protected $fillable = [
        'code',
        'description',
        'denomination',
        'brand_manufacturer',
        'model',
        'serie',
        'observations_technique',
        'observations_certificate',
        'range_capacity',
        'scope_resolution',
        'date_entry',
        'operational_status',
        'last_calibration',
        'next_calibration',
        'executed_calibration',
        'conformity',
        'frequency',
        'last_verification',
        'next_verification',
        'executed_verification',
        'accordance',
        'calibration_points_verification',
        'acceptance_criteria',
        'active',
        'area_id',
        'status',
        'os',
    ];

    protected $casts = [
        'active' => 'boolean',
        'created_at' => LocalTimezone::class,
        'date_entry' => LocalTimezone::class,
        'last_calibration' => LocalTimezone::class,
        'next_calibration' => LocalTimezone::class,
        'last_verification' => LocalTimezone::class,
        'next_verification' => LocalTimezone::class,
    ];

    protected array $localTimezoneDateOnly = [
        'date_entry',
        'last_calibration',
        'next_calibration',
        'last_verification',
        'next_verification',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class, 'area_id');
    }
}
