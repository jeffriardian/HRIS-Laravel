<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Memorandum extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'interrogation_report_actor_id','memorandum_parameter_id','start_date','end_date','description','is_active'
    ];

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
    public function interrogationReportActor(){
        return $this->belongsTo(InterrogationReportActor::class);
    }
    public function memorandumParameter(){
        return $this->belongsTo(MemorandumParameter::class);
    }
}
