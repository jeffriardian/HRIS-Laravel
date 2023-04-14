<?php

namespace Modules\Engineering\Models;

use Illuminate\Database\Eloquent\Model;

use App\Traits\Signature;

class BacklogPart extends Model
{
    use Signature;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['backlog_id','part','quantity'];

    public function backlog(){
        return $this->belongsTo(Backlog::class);
    }
}
