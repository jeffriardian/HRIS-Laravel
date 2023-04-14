<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CandidateReference extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['candidate_id','name','address','phone','job','is_active'];

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
}
