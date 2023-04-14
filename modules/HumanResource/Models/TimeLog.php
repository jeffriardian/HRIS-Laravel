<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class TimeLog extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['employee_id', 'in', 'out', 'is_active'];
}
