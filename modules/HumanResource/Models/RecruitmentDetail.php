<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class RecruitmentDetail extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recruitment_id','recruitment_step_parameter_id','score_id','candidate_id','recruitment_step_id','note','is_active'
    ];

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
    
    public function recruitmentStep(){
        return $this->belongsTo(RecruitmentStep::class);
    }

    public function recruitmentStepParameter(){
        return $this->belongsTo(RecruitmentStepParameter::class);
    }

    public function score(){
        return $this->belongsTo(Score::class);
    }

    public function recruitment(){
        return $this->belongsTo(Recruitment::class);
    }
}
