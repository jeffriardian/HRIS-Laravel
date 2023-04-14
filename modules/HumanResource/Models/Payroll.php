<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Payroll extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['payroll_period_id', 'employee_id', 'salary', 'allowance',
    'deduction', 'benefit', 'bruto', 'netto', 'is_active'];
}
