<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends TenantModel
{
    use SoftDeletes;

    protected $table = 'items';

    protected $fillable = [
        'type',
        'type_of_sample_id',
        'condition_id',
        'matrix_id',
        'reference_id',
        'parameter_id',
        'unit_measurement_id',
        'lcm',
        'is_operation',
        'operations',
        'is_other_company',
        'company_id',
        'unit_price',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
        'is_other_company' => 'boolean',
        'is_operation' => 'boolean',
        'operations' => 'json',
    ];

    public function typeOfSample(): BelongsTo
    {
        return $this->belongsTo(TypeOfSamples::class, 'type_of_sample_id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Conditions::class, 'condition_id');
    }

    public function matrix(): BelongsTo
    {
        return $this->belongsTo(Matrix::class, 'matrix_id');
    }

    public function reference(): BelongsTo
    {
        return $this->belongsTo(ReferencesStandard::class, 'reference_id');
    }

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameters::class, 'parameter_id');
    }

    public function unitMeasurement(): BelongsTo
    {
        return $this->belongsTo(UnitsMeasurement::class, 'unit_measurement_id');
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
