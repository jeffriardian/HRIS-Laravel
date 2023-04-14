<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class DepartmentLevel extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['code','name', 'description','is_active'];

    public function departments(){
        return $this->hasMany(Department::class);
    }
}
