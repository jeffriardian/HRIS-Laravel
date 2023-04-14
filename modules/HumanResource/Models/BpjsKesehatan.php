<?php

namespace Modules\HumanResource\Models;

use App\Traits\Signature;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class BpjsKesehatan extends Model
{
    use Signature, LogsActivity;

    protected $fillable = [
        'card_number',
        'employee_salary_deduction',
        'office_salary_deduction',
        'date',
        'office'
    ];

    protected static $logName = "Bpjs Kesehatan";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
}
