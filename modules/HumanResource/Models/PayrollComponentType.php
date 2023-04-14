<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class PayrollComponentType extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['component_type_name', 'is_active'];
}
