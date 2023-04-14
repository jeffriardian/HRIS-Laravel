<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

use Modules\Production\Models\Machine;
use Modules\HumanResource\Models\Employee;

class JobCard extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'work_order_id','machine_id','employee_id',
        'code','start_at','stop_at','description','is_active'
    ];

    public function workOrder(){
        return $this->belongsTo(WorkOrder::class);
    }

    public function machine(){
        return $this->belongsTo(Machine::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }

    public function partOrders(){
        return $this->hasMany(JobCardPartOrder::class);
    }

    public function partUsed(){
        return $this->hasMany(JobCardPartUsed::class);
    }

    public function partComplains(){
        return $this->hasMany(JobCardPartComplain::class);
    }
}
