<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConnectionParameter extends TenantModel
{
    protected $table = 'connection_parameter';

    protected $fillable = [
        'parameter_id',
        'matrix_id',
        'type_of_samples_id',
    ];

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameters::class, 'parameter_id');
    }

    public function matrix(): BelongsTo
    {
        return $this->belongsTo(Matrix::class, 'matrix_id');
    }

    public function typeOfSample(): BelongsTo
    {
        return $this->belongsTo(TypeOfSamples::class, 'type_of_samples_id');
    }
}
