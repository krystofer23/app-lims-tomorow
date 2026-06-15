<?php

namespace App\Models\tenant;

use App\Models\tenant\ChainCustody;
use App\Models\tenant\Item;
use App\Models\tenant\Matrix;
use App\Models\tenant\OrderService;
use App\Models\tenant\Parameters;
use App\Models\tenant\TypeOfSamples;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaboratoryResults extends Model
{
    use SoftDeletes;

    protected $table = 'laboratory_results';

    protected $fillable = [
        'order_id',
        'order_item_id',
        'parameter_id',
        'matrix_id',
        'type_of_sample_id',
        'item_id',
        'chain_custody_id',
        'code_season',
        'code_lab',
        'code_sample',
        'result',
        'condition_id',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderService::class, 'order_id');
    }

    public function chainCustody(): BelongsTo
    {
        return $this->belongsTo(ChainCustody::class, 'chain_custody_id');
    }

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
        return $this->belongsTo(TypeOfSamples::class, 'type_of_sample_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function condition(): BelongsTo
    {
        return $this->belongsTo(Conditions::class, 'condition_id');
    }
}
