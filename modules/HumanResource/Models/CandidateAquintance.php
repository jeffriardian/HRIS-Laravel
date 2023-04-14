<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CandidateAquintance extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['candidate_id','employee_id','relation','is_active'];

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }

    public function employee(){
        return $this->belongsTo(Employee::class);
    }
}
