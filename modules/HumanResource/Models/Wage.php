<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Wage extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['amount','is_active'];
}
