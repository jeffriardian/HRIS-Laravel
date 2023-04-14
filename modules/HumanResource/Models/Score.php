<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Score extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = [
        'name','score','is_active'
    ];

    public function recruitmentDetail(){
        return $this->hasMany(recruitmentDetail::class);
    }
    
    public function recruitmentStepParameterScores(){
        return $this->hasMany(RecruitmentStepParameterScores::class);
    }
}
