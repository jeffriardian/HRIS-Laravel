<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Overtime extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = [
        'employee_id',
        'approved_by',
        'request_date',
        'overtime_before_duration',
        'overtime_after_duration',
        'total_time',
        'attachment',
        'description',
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

    public function overtimeEmployee()
    {
        return $this->hasMany(OvertimeEmployee::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(Employee::class, 'approved_by', 'id');
    }

    public function shift()
    {
        return $this->hasOne(OvertimeShiftEmployee::class);
    }

    public function approval()
    {
        return $this->hasOne(OvertimeApproval::class);
    }

    public function histories()
    {
        return $this->hasMany(OvertimeHistory::class);
    }

    public function overtimeRates()
    {
        return $this->hasOne(OvertimeRates::class);
    }
}
