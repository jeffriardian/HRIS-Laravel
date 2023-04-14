<?php

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class MachinePart extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['machine_group_id','code', 'name','is_active'];

    public function machineGroup(){
        return $this->belongsTo(MachineGroup::class);
    }
}
