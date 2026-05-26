<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Matrix extends TenantModel
{
    use SoftDeletes;

    protected $table = 'matrix';

    protected $fillable = [
        'description',
        'type_of_sample_id',
    ];

    public function typeOfSample(): BelongsTo
    {
        return $this->belongsTo(TypeOfSamples::class, 'type_of_sample_id');
    }
}
