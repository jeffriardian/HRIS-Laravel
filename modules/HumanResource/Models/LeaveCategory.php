<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class LeaveCategory extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['name', 'total' ,'is_active'];

    public function leave(){
        return $this->hasMany(Leave::class);
    }
}
