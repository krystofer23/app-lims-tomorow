<?php

namespace App\Models\tenant;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProceduresToParameter extends Model
{
    use SoftDeletes;

    protected $table = 'procedures_to_parameter';

    protected $fillable = [
        'parameter_id',
        'procedure_id',
    ];

    public function parameter(): BelongsTo
    {
        return $this->belongsTo(Parameters::class, 'parameter_id');
    }

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class, 'procedure_id');
    }
}
