<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OvertimeRates extends Model
{
    use SoftDeletes;
    protected $fillable = ['overtime_id', 'L1', 'rates_L1', 'L2', 'rates_L2', 'L3', 'rates_L3', 'total_rates'];

    public function overtime()
    {
        return $this->belongsTo(Overtime::class);
    }
}
