<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class BloodType extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['name', 'description','is_active'];
}
