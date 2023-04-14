<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;
use Spatie\Activitylog\Traits\LogsActivity;

class BusinessTrip extends Model
{
    use SoftDeletes, Signature, LogsActivity;

    protected $fillable = [
        'business_trip_number', 'receipt_number', 'employee_id', 'departure_date',
        'back_date', 'destination', 'purpose', 'total_cost', 'approval_status_id',
        'business_trip_result'
    ];

    protected static $logName = "Business Trip";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function parameters()
    {
        return $this->hasMany(BusinessTripParameter::class);
    }

    public function transportations()
    {
        return $this->hasMany(BusinessTripTransportation::class);
    }

    public function reimburse()
    {
        return $this->hasOne(Reimburse::class, 'reimburse_number', 'business_trip_number');
    }

    public function images()
    {
        return $this->hasMany(BusinessTripImage::class);
    }
}
