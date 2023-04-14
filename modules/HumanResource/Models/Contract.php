<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Contract extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['name', 'description','is_active'];

    public function employeeStatuses(){
        return $this->hasMany(EmployeeStatus::class);
    }
}
