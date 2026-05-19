<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parameters extends TenantModel
{
    use SoftDeletes;

    protected $table = 'parameters';

    protected $fillable = [
        'description',
        'type_of_analysis_id',
    ];

    public function typeOfAnalysis(): BelongsTo
    {
        return $this->belongsTo(TypeOfAnalysis::class, 'type_of_analysis_id');
    }

    public function item(): HasMany
    {
        return $this->hasMany(Item::class, 'parameter_id', 'id');
    }

    public function connectionsParameter(): HasMany
    {
        return $this->hasMany(ConnectionParameter::class, 'parameter_id', 'id');
    }
}
