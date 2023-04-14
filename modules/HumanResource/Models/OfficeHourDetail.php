<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class OfficeHourDetail extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['office_hour_id', 'weekday_in', 'weekday_out', 'weekend_in', 'weekend_out', 'is_active'];

    public function officeHour(){
        return $this->belongsTo(OfficeHour::class);
    }
}
