<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CooperativeLoanTransaction extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['cooperative_member_id', 'cooperative_description_id', 'amount', 'is_active'];
}
