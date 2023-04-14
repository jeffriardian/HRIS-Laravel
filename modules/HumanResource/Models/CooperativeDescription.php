<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CooperativeDescription extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['cooperative_category_id', 'cooperative_type_id', 'name', 'is_active'];
}
