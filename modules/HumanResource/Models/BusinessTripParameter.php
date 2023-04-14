<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class BusinessTripParameter extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['business_trip_id', 'receipt_parameter_id', 'cost', 'image', 'dimensions', 'path', 'is_active'];

    protected static $logName = "Business Trip Parameters";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function businessTrip()
    {
        return $this->belongsTo(BusinessTrip::class);
    }

    public function receiptParameter()
    {
        return $this->belongsTo(ReceiptParameter::class);
    }
}
