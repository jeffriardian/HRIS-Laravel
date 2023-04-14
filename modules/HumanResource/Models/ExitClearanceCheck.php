<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class ExitClearanceCheck extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['exit_clearance_id', 'exit_clearance_parameter_id', 'status'];
}
