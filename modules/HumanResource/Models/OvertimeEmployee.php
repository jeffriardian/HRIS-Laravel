<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class OvertimeEmployee extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['overtime_id', 'employee_id', 'approval_status_id'];

    public function overtime(){
        return $this->belongsTo(Overtime::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
