<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Insurance extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['bpjstk', 'bpjskes', 'is_active'];
}
