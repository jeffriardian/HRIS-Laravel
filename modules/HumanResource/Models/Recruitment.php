<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Recruitment extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'candidate_id','status_recruitment_id','recruitment_step_id','recruitment_date','total_score','note','is_active'
    ];

    public function recruitmentStep(){
        return $this->belongsTo(RecruitmentStep::class);
    }

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }

    public function recruitmentDetails(){
        return $this->hasMany(RecruitmentDetail::class);
    }

    public function recruitmentFiles(){
        return $this->hasMany(RecruitmentFile::class);
    }
}
