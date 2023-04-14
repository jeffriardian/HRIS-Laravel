<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Leave extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['leave_category_id', 'employee_id', 'start_date', 'end_date', 'total', 'is_active'];

    public function leaveCategory(){
        return $this->belongsTo(LeaveCategory::class);
    }
}
