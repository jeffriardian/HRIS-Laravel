<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class StatusRecruitment extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = [
        'name','is_active'
    ];

    public function scheduleRecruitment(){
        return $this->hasMany(scheduleRecruitment::class);
    }
}
