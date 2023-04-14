<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CandidateFile extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['candidate_id','caption','type','note','is_active'];

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
}
