<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

use Modules\Production\Models\MachineGroup;

class EngineeringMonthlyPlanningDetail extends Model
{
    use Signature;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['engineering_monthly_planning_id','machine_group_id','ps1','ps2','ps3','ps4'];

    protected $guarded = [];

    public function monthlyPlanning(){
        return $this->belongsTo(EngineeringMonthlyPlanning::class);
    }

    public function machineGroup(){
        return $this->belongsTo(MachineGroup::class);
    }
}
