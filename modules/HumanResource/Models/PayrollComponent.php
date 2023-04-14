<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class PayrollComponent extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['payroll_component_type_id', 'status', 'time', 'name', 'effective_date', 'taxable', 'is_active'];

    public function employeeSalaries()
    {
        return $this->hasMany(EmployeeSalary::class);
    }
}
