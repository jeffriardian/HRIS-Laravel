<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Attendance extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['employee_id', 'date_work', 'time_in', 'in_time', 'time_out', 'out_time',
     'status', 'total_time_work', 'is_active'];
}
