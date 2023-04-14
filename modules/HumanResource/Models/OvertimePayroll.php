<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;


class OvertimePayroll extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = [
        'employee_id', 'date_overtime',
        'schedule_start_overtime', 'start_overtime',
        'schedule_end_overtime', 'end_overtime',
        'overtime_1', 'amount_overtime_1', 'total_overtime_1',
        'overtime_2', 'amount_overtime_2', 'total_overtime_2',
        'overtime_weekend_2', 'amount_overtime_weekend_2', 'total_overtime_weekend_2',
        'overtime_weekend_3', 'amount_overtime_weekend_3', 'total_overtime_weekend_3',
        'total_overtime', 'is_active'
    ];
}
