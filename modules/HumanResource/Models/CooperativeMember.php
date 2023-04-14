<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class CooperativeMember extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['company_id', 'department_id', 'employee_id', 'cooperative_member_type_id', 'id_member', 'name',
    'join_date', 'end_date', 'saving_balance', 'loan_balance', 'installment_count', 'installment_payment', 'is_active'];
}
