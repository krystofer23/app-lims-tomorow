<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChainCustody extends TenantModel
{
    protected $table = 'chain_custody';

    protected $fillable = [
        'company_id',
        'application_id',
        'order_id',
        'os',
        'number_chain',
        'number_report',
        'type_of_sample_id',
        'matrix_id',
        'number_sample',
        'number_essays',
        'date_reception',
        'date_sampling_init_date',
        'date_sampling_init_time',
        'date_sampling_end_date',
        'date_sampling_end_time',
        'date_agreed',
        'company_sampling_id',
        'code_lab',
        'code_season',
        'condition_report',
        'other_company_id',
        'observations',
        'parameters',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
        'parameters' => 'json',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'application_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_id');
    }

    public function typeOfSample(): BelongsTo
    {
        return $this->belongsTo(TypeOfSamples::class, 'type_of_sample_id');
    }

    public function matrix(): BelongsTo
    {
        return $this->belongsTo(Matrix::class, 'matrix_id');
    }
}
