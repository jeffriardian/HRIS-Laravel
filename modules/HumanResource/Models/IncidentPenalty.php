<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class IncidentPenalty extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['interrogation_report_actor_id', 'penalty', 'installment_count', 'type_of_installment', 'period_of_time', 'type_of_period', 'early_payment','is_active'];

    public function interrogationReportActor(){
        return $this->belongsTo(InterrogationReportActor::class);
    }
    public function incidentPenaltyDetails(){
        return $this->hasMany(IncidentPenaltyDetail::class);
    }
}
