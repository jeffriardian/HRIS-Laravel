<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;
use Modules\Production\Models\Machine;

class FailureAnalysis extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['machine_id','part', 'description','is_active'];

    public function machine(){
        return $this->belongsTo(Machine::class);
    }
}
