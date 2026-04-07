<?php

namespace App\Models\tenant;

use App\Casts\LocalTimezone;
use App\Models\TenantModel;
use Illuminate\Database\Eloquent\Model;

class RelationEssayTeam extends TenantModel
{
    protected $table = 'relations_essay_team';

    protected $fillable = [
        'essay_id',
        'team_id',
    ];

    protected $casts = [
        'created_at' => LocalTimezone::class,
    ];
}
