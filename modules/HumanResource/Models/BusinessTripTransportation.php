<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class BusinessTripTransportation extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['business_trip_id', 'transportation_id', 'cost', 'is_active'];

    protected static $logName = "Business Trip Transportations";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function businessTrip()
    {
        return $this->belongsTo(BusinessTrip::class);
    }

    public function transportation()
    {
        return $this->belongsTo(Transportation::class);
    }
}
