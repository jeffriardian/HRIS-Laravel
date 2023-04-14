<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class IncidentPenaltyDetail extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['incident_penalty_id', 'amount', 'precentage', 'due_date','is_active'];

    public function incidentPenalty(){
        return $this->belongsTo(IncidentPenalty::class);
    }
}
