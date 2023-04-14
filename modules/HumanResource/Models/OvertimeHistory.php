<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeHistory extends Model
{
    protected $fillable = ['overtime_id', 'overtime_before_duration', 'overtime_after_duration', 'total_time', 'approved_status', 'reject_description'];

    public function overtime()
    {
        return $this->belongsTo(Overtime::class);
    }
}
