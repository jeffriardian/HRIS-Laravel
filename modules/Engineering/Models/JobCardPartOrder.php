<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

class JobCardPartOrder extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['job_card_id','part','quantity'];

    public function jobCard(){
        return $this->belongsTo(JobCard::class);
    }
}
