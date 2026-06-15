<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrialPeriod extends TenantModel
{
    use SoftDeletes;

    protected $table = 'trial_period';

    protected $fillable = [
        'date_init',
        'date_end',
        'order_id',
        'type_of_sample_id',
        'condition_id',
    ];

    protected $casts = [
        'date_init' => 'date',
        'date_end' => 'date',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_id');
    }

    public function typeOfSample(): BelongsTo
    {
        return $this->belongsTo(TypeOfSamples::class, 'type_of_sample_id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Conditions::class, 'condition_id');
    }
}
