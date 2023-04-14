<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class PayrollPeriod extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['company_id', 'start_period', 'end_period', 'date_created', 'is_active'];
}
