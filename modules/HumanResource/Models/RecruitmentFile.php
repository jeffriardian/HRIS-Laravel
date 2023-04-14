<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;
class RecruitmentFile extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['recruitment_id','caption','type','note','is_active'];

    public function recruitment(){
        return $this->belongsTo(Recruitment::class);
    }
}
