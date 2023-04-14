<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class HistoryEmployeeStatus extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['employee_id', 'employee_status_id', 'start_date', 'end_date', 'is_active'];
}
