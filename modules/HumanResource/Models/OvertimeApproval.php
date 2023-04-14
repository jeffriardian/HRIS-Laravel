<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class OvertimeApproval extends Model
{
    protected $fillable = ['overtime_id', 'approved_by', 'approved_status', 'reject_description'];

    public function overtime()
    {
        return $this->belongsTo(Overtime::class);
    }
}
