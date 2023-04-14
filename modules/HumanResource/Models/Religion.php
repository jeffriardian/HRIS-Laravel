<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Religion extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['name', 'worship_place', 'description','is_active'];
}
