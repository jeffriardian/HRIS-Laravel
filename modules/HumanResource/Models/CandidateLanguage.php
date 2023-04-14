<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;
class CandidateLanguage extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['candidate_id','language','speaking','writing','reading','is_active'];

    public function candidate(){
        return $this->belongsTo(Candidate::class);
    }
}
