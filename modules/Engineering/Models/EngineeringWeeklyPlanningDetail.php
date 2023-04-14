<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

use Modules\Production\Models\MachineGroup;

class EngineeringWeeklyPlanningDetail extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['engineering_weekly_planning_id','machine_group_id','1','2','3','4','5','6','7','is_active'];

    public function weeklyPlanning(){
        return $this->belongsTo(EngineeringWeeklyPlanning::class);
    }

    public function machineGroup(){
        return $this->belongsTo(MachineGroup::class);
    }
}
