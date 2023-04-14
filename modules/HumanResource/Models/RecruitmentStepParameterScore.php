<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class RecruitmentStepParameterScore extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recruitment_step_parameter_id','score_id','description','is_active'
    ];

    public function recruitmentStepParameter(){
        return $this->belongsTo(RecruitmentStepParameter::class);
    }

    public function score(){
        return $this->belongsTo(Score::class);
    }
}
