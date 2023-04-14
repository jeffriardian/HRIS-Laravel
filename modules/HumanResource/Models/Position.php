<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Position extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['code','name', 'description','is_active'];
}
