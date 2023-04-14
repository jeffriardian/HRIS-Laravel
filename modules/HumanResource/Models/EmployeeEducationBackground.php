<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class EmployeeEducationBackground extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['employee_id','school_name','major','city','entry_year','graduation_year','score','is_active'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
