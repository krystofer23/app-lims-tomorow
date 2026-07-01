<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaboratoryRniResult extends Model
{
    use SoftDeletes;

    protected $table = 'laboratory_rni_results';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'item_id',
        'parameter_id',
        'matrix_id',
        'type_of_sample_id',
        'chain_custody_id',
        'measurement_period',

        'date_monitoring',
        'hour_sampling',
        'humidity_relative',
        'ambient_temperature',
        'electric_system_type',

        'instrument',
        'brand',
        'model',
        'serial_number',
        'probe_range',
        'calibration_date',
        'certificate_number',

        'station_description',
        'soil_coverage',
        'climate_conditions',

        'measurements',
        'summary',
    ];

    protected $casts = [
        'measurements' => 'array',
        'summary' => 'array',
        'date_monitoring' => 'date',
        'calibration_date' => 'date',
    ];
}
