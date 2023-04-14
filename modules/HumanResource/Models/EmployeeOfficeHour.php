<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeOfficeHour extends Model
{
    protected $fillable = ['employee_id', 'office_hour_id', 'order'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function officeHour()
    {
        return $this->belongsTo(OfficeHour::class);
    }
}
