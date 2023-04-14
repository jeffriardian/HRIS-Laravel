<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

use Modules\Production\Models\Machine;

class EngineeringAnnualPlanningDetail extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //protected $fillable = ['engineering_annual_planning_id','machine_id','service_type_id','week', 'month','is_active'];
    
    protected $guarded = [];

    public function annualPlanning(){
        return $this->belongsTo(EngineeringAnnualPlanning::class);
    }

    public function serviceType(){
        return $this->belongsTo(ServiceType::class);
    }

    public function machine(){
        return $this->belongsTo(Machine::class);
    }
}
