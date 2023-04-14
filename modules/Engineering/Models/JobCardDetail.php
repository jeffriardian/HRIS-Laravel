<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

class JobCardDetail extends Model
{
    protected $fillable = [];

    public function jobCard(){
        return $this->belongsTo(JobCard::class);
    }
}
