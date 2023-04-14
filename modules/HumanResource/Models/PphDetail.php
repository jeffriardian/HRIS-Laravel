<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class PphDetail extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['pph_id', 'parameter_id', 'amount', 'is_active'];
}
