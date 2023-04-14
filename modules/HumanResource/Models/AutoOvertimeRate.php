<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AutoOvertimeRate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'employee_id',
        'date',
        'total_times',
        'L1',
        'rates_L1',
        'L2',
        'rates_L2',
        'L3',
        'rates_L3',
        'total_rates'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
