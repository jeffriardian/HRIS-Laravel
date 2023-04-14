<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class RecruitmentStepParameter extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'recruitment_step_id','name','description','is_active'
    ];

    public function recruitmentStep(){
        return $this->belongsTo(RecruitmentStep::class);
    }

    public function recruitmentDetail(){
        return $this->hasMany(RecruitmentDetail::class);
    }

    public function recruitmentStepParameterScores(){
        return $this->hasMany(RecruitmentStepParameterScore::class);
    }
}
