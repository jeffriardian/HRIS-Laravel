<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeShiftEmployee extends Model
{
    protected $fillable = ['overtime_id', 'shift_id', 'schedule_in', 'schedule_out'];

    public function overtime()
    {
        return $this->belongsTo(Overtime::class);
    }

    public function shift()
    {
        return $this->belongsTo(OfficeHour::class);
    }
}
