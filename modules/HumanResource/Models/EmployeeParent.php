<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class EmployeeParent extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['employee_id','type','name','date_of_birth','gender','address','phone','is_active'];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
