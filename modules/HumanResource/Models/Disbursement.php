<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class Disbursement extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['reimburse_id', 'amount', 'date_process', 'disbursement_type', 'is_active'];
}
