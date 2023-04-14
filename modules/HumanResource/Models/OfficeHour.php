<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class OfficeHour extends Model
{
    use SoftDeletes, Signature;

    // protected $fillable = ['name', 'is_active'];
    protected $fillable = ['name', 'weekday_in', 'weekday_out', 'weekend_in', 'weekend_out', 'is_active'];

    // public function details(){
    //     return $this->hasMany(OfficeHourDetail::class);
    // }

    // public function details()
    // {
    //     return $this->hasOne(OfficeHourDetail::class);
    // }

    public function dummy()
    {
        return $this->hasMany(DummyAttendance::class, 'shift_id', 'id');
    }

    public function consecutiveHoliday()
    {
        return $this->hasMany(ConsecutiveHoliday::class);
    }
}
