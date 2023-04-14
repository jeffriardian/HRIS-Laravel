<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class EmployeeSalary extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['employee_id', 'payroll_component_id', 'amount', 'is_active'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrollComponent()
    {
        return $this->belongsTo(PayrollComponent::class);
    }
}
