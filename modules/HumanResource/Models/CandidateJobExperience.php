<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CandidateJobExperience extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['candidate_id','company_name','address','phone','position','start_date','end_date','is_active'];

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
}
