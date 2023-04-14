<?php

namespace Modules\Production\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class MachineGroup extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['code','name', 'description','is_active'];

    public function machines(){
        return $this->hasMany(Machine::class);
    }

    public function parts(){
        return $this->hasMany(MachinePart::class);
    }
}
