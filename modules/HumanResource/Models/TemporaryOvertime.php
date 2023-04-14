<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryOvertime extends Model
{
    protected $fillable = [
        'employee_id',
        'approved_by',
        'request_date',
        'overtime_before_duration',
        'overtime_after_duration',
        'total_time',
        'attachment',
        'description',
        'shift_id',
        'schedule_in',
        'schedule_out',
        'without_reducing_rest_hours',
        'is_holiday',
        'is_saturday',
        'start_time_holiday',
        'end_time_holiday'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'id');
    }
}
