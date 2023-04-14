<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class OfficeHourSpecialDetail extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['office_hour_special_id', 'weekday_in', 'weekday_out', 'weekend_in', 'weekend_out', 'is_active'];

    public function officeHourSpecial()
    {
        return $this->belongsTo(OfficeHourSpecial::class);
    }
}
