<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;
class TrainingEmployee extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['training_id','employee_id','is_active'];

    public function training(){
        return $this->belongsTo(Training::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
