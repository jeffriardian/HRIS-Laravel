<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessTripImage extends Model
{
    protected $fillable = ['business_trip_id', 'image', 'payment_slip_type', 'payment_slip_id'];

    protected static $logName = "Business Trip Images";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function businessTrip()
    {
        return $this->belongsTo(BusinessTrip::class);
    }
}
