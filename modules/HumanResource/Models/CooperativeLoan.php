<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CooperativeLoan extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['cooperative_member_id', 'loan_date', 'amount', 'interest', 'total', 'is_active'];
}
