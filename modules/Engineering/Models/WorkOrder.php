<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

use Modules\Production\Models\MachineGroup;
use Modules\HumanResource\Models\Department;

class WorkOrder extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'work_order_action_id','machine_group_id','service_type_id','department_id',
        'source','type','code','description','is_urgent'
    ];

    public function workOrderAction(){
        return $this->belongsTo(WorkOrderAction::class);
    }

    public function machineGroup(){
        return $this->belongsTo(MachineGroup::class);
    }

    public function serviceType(){
        return $this->belongsTo(ServiceType::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }
}
