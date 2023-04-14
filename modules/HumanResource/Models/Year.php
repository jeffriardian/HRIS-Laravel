<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Year extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['id', 'name', 'is_active'];
}
