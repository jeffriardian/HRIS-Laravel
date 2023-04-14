<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

use Modules\Production\Models\Machine;

class Backlog extends Model
{
    use SoftDeletes, Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'job_card_id','machine_id','failure_type_id',
        'estimation_repair_time','estimation_endurance_time','description','is_active'
    ];

    public function parts(){
        return $this->hasMany(BacklogPart::class);
    }

    public function jobCard(){
        return $this->belongsTo(JobCard::class);
    }

    public function machine(){
        return $this->belongsTo(Machine::class);
    }

    public function failureType(){
        return $this->belongsTo(FailureType::class);
    }
}
