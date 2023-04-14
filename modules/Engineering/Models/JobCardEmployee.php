<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

use Modules\HumanResource\Models\Employee;

class JobCardEmployee extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_card_id','employee_id'];

    public function jobCard(){
        return $this->belongsTo(JobCard::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
