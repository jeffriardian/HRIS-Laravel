<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Reimburse extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = [
        'reimburse_category_id', 'employee_id', 'reimburse_number', 'description', 'total_cost',
        'reimburse_type', 'approval_status_id', 'is_active'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reimburseNUmber()
    {
        return $this->belongsTo(BusinessTrip::class, 'reimburse_number', 'business_trip_number');
    }

    public function fileReimburses(){
        return $this->hasMany(FileReimburse::class);
    }
}
