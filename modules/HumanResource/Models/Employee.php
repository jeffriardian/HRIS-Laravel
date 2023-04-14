<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

use App\Traits\Signature;
use Spatie\Activitylog\Traits\LogsActivity;

class Employee extends Model
{
    use Notifiable, SoftDeletes, Signature, LogsActivity;

    protected static $logName = "Employee";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    const GENDER_MAN = 1;
    const GENDER_WOMAN = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'department_id',
        'position_id',
        'religion_id',
        'blood_type_id',
        'marital_status_id',
        'employee_status_id',
        'company_id',
        'office_hour_id',
        'payroll_type_id',
        'salary_type_id',
        'day_off_id',
        'ptkp_code',
        'nik',
        'old_nik',
        'ktp',
        'kk',
        'npwp',
        'sim',
        'pin',
        'name',
        'email',
        'phone',
        'address',
        'address_ktp',
        'photo',
        'gender',
        'birth_place',
        'birth_at',
        'join_at',
        'leave_at',
        'is_active'
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    public function contacts()
    {
        return $this->hasMany(EmployeeContact::class);
    }

    public function salaries()
    {
        return $this->hasMany(EmployeeSalary::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function payrollType()
    {
        return $this->belongsTo(PayrollType::class);
    }

    public function salaryType()
    {
        return $this->belongsTo(SalaryType::class);
    }

    public function businessTrip()
    {
        return $this->hasMany(BusinessTrip::class);
    }

    public function overtimeEmployee()
    {
        return $this->hasMany(OvertimeEmployee::class);
    }

    public function cooperative()
    {
        return $this->hasMany(Cooperative::class);
    }

    public function loan()
    {
        return $this->hasMany(Loan::class);
    }

    public function reimburses()
    {
        return $this->hasMany(Reimburse::class);
    }

    public function trainingEmployees()
    {
        return $this->hasMany(TrainingEmployee::class);
    }

    public function improvementIdeas()
    {
        return $this->hasMany(ImprovementIdea::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function dayOff()
    {
        return $this->belongsTo(DayOff::class);
    }

    public function interrogationReportActors()
    {
        return $this->hasMany(InterrogationReportActor::class);
    }

    public function officeHours()
    {
        return $this->hasMany(EmployeeOfficeHour::class);
    }

    public function consecutiveHoliday()
    {
        return $this->hasOne(ConsecutiveHoliday::class);
    }

    public function autoOvertimeRates()
    {
        return $this->hasMany(AutoOvertimeRate::class);
    }
}
