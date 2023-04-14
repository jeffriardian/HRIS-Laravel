<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class DummyAttendance extends Model
{
    protected $fillable = [];

    public function shift()
    {
        return $this->belongsTo(OfficeHour::class, 'shift_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_code', 'nik');
    }
}
