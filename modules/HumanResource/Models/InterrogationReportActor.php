<?php

namespace Modules\HumanResource\Models;

use App\Traits\Signature;
use Illuminate\Database\Eloquent\Model;

class InterrogationReportActor extends Model
{
    use Signature;

    protected $fillable = ['interrogation_report_id', 'interrogation_report_actor_type_id', 'external_employee_id','employee_id','description'];

    public function interrogationReport()
    {
        return $this->belongsTo(InterrogationReport::class);
    }

    public function incidentPenalties()
    {
        return $this->hasMany(IncidentPenalty::class);
    }

    public function interrogationReportActorType()
    {
        return $this->belongsTo(InterrogationReportActorType::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function externalEmployee()
    {
        return $this->belongsTo(ExternalEmployee::class);
    }
    public function memorandums()
    {
        return $this->hasMany(Memorandum::class);
    }
}
