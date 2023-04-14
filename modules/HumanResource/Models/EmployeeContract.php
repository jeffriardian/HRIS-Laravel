<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class EmployeeContract extends Model
{
    use SoftDeletes, Signature;

    const TYPE_PHONE = 1;
    const TYPE_EMAIL = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'employee_id','start_at','end_at',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
