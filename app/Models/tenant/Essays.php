<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Essays extends TenantModel
{
    use SoftDeletes;

    protected $table = 'essays';

    protected $fillable = [
        'description',
        'lcm',
        'units_measurement_id',
        'condition_id',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class
    ];

    public function unitsMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitsMeasurement::class, 'units_measurement_id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Conditions::class, 'condition_id');
    }
}
