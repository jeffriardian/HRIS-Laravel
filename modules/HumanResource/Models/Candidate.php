<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;


class Candidate extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'department_id',
        'position_id',
        'religion_id',
        // 'blood_type_id',
        // 'payroll_type_id',
        'marital_status_id',
        'company_id',
        'photo',
        'ktp',
        'name',
        'gender',
        'address',
        'post_code',
        'birth_place',
        'birth_at',
        'has_sim',
        'sim',
        'email',
        'phone_number',
        'achivement',
        'thesis_title',
        'last_salary',
        'last_facility',
        'achivement_during_work',
        'last_position',
        'last_job_desc',
        'applying_reason',
        'work_environment',
        'expected_salaries',
        'expected_facilities',
        'work_out_of_town',
        'placed_out_of_town',
        'work_accident',
        'date_of_accident',
        'psychological_check',
        'date_of_check',
        'check_place',
        'check_purpose',
        'vehicle_type',
        'vehicle_belong',
        'employees_name',
        'employees_relationship',
        'is_active',
    ];

    public function candidateFiles(){
        return $this->hasMany(CandidateFile::class);
    }
    
    public function candidateChildrens(){
        return $this->hasMany(CandidateChildren::class);
    }

    public function candidateCouples(){
        return $this->hasMany(CandidateCouple::class);
    }

    public function candidateEmergencies(){
        return $this->hasMany(CandidateEmergency::class);
    }

    public function candidateJobExperiences(){
        return $this->hasMany(CandidateJobExperience::class);
    }

    public function candidateLanguages(){
        return $this->hasMany(CandidateLanguage::class);
    }

    public function candidateParents(){
        return $this->hasMany(CandidateParent::class);
    }

    public function candidateReferences(){
        return $this->hasMany(CandidateReference::class);
    }

    public function candidateAquintances(){
        return $this->hasMany(CandidateAquintance::class);
    }

    public function candidateSiblings(){
        return $this->hasMany(CandidateSibling::class);
    }

    public function candidateTrainings(){
        return $this->hasMany(CandidateTraining::class);
    }

    public function candidateEducationBackgrounds(){
        return $this->hasMany(CandidateEducationBackground::class);
    }
    
    public function company(){
        return $this->belongsTo(Company::class);
    }
    
    public function position(){
        return $this->belongsTo(Position::class);
    }

    public function religion(){
        return $this->belongsTo(Religion::class);
    }

    public function bloodType(){
        return $this->belongsTo(BloodType::class);
    }
    public function maritalStatus(){
        return $this->belongsTo(MaritalStatus::class);
    }

    public function payrollType(){
        return $this->belongsTo(PayrollType::class);
    }

    public function recruitment(){
        return $this->hasMany(Recruitment::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

}
