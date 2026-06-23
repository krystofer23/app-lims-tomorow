<?php

namespace App\Models\tenant;

use App\Models\TenantModel;
use App\Models\tenatn\SelectToMetals;
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
        'ids_connections_parameters',
        'is_metal'
    ];

    protected $casts = [
        'ids_connections_parameters' => 'array',
        'is_metal' => 'boolean'
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

    public function toMetal(): BelongsTo
    {
        return $this->belongsTo(SelectToMetals::class, 'to_metal_id');
    }
}
