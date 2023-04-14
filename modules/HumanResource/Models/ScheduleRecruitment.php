<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class ScheduleRecruitment extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'candidate_id','recruitment_step_id','status_recruitment_id','recruitment_date','is_active'
    ];

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }

    public function recruitment(){
        return $this->belongsTo(Recruitment::class);
    }

    public function recruitmentStep(){
        return $this->belongsTo(RecruitmentStep::class);
    }
    public function statusRecruitment(){
        return $this->belongsTo(StatusRecruitment::class);
    }
}
