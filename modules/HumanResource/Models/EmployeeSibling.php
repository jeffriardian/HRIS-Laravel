<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class EmployeeSibling extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['employee_id','name','date_of_birth','gender','is_active'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
