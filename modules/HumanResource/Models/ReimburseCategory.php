<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class ReimburseCategory extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['name', 'is_active'];
}
