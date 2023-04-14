<?php

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Machine extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['machine_group_id', 'machine_type_id', 'machine_brand_id','code', 'area', 'year', 'description','is_active'];

    public function machineGroup(){
        return $this->belongsTo(MachineGroup::class);
    }
    
    public function machineBrand(){
        return $this->belongsTo(MachineBrand::class);
    }

    public function machineType(){
        return $this->belongsTo(MachineType::class);
    }
}
