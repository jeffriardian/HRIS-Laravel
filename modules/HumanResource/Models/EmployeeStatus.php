<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class EmployeeStatus extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['contract_id', 'period_id','is_active'];

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    public function period(){
        return $this->belongsTo(Period::class);
    }
}
