<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class PphParameter extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['parameter_code', 'nama_parameter_pph', 'tipe_pph', 'status_parameter_pph', 'is_active'];
}
