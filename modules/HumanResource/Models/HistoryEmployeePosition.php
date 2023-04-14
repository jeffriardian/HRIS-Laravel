<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class HistoryEmployeePosition extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['employee_id', 'department_id', 'position_id', 'start_date', 'file' ,'is_active'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}
