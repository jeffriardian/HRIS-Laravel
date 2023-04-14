<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;
use Modules\HumanResource\Models\Employee;

class EngineeringImprovement extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['employee_id','name', 'description','is_active'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
