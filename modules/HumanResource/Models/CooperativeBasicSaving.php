<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CooperativeBasicSaving extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['cooperative_member_type_id', 'amount', 'is_active'];
}
