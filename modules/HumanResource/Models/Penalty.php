<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Penalty extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['incident_penalty_id', 'amount', 'date_process', 'is_active'];
}
