<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class RecruitmentStep extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name','min_score','description','is_active'
    ];

    public function recruitmentStepParameter(){
        return $this->hasMany(RecruitmentStepParameter::class);
    }
    

    public function scheduleRecruitment(){
        return $this->hasMany(ScheduleRecruitment::class);
    }

    public function recruitment(){
        return $this->hasMany(Recruitment::class);
    }

    

}
