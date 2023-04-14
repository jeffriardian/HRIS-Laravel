<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

use Modules\Production\Models\Machine;

class EngineeringDailyPlanningDetail extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['engineering_daily_planning_id','machine_id','service_type_id','stop_at', 'start_at','is_active'];

    public function weeklyPlanning(){
        return $this->belongsTo(EngineeringWeeklyPlanning::class);
    }

    public function serviceType(){
        return $this->belongsTo(ServiceType::class);
    }

    public function machine(){
        return $this->belongsTo(Machine::class);
    }
}
