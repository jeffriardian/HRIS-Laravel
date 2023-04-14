<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Pph extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['employee_id', 'salary', 'company_allowance', 'bruto', 'deduction', 'netto_month', 'netto_year',
    'ptkp', 'pkp', 'pph21_owed_year', 'pph21_owed_month', 'month_period', 'year_period', 'is_active'];
}
