<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class ReceiptParameter extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['name', 'is_active'];

    public function businessTripParameter(){
        return $this->hasMany(BusinessTripParameter::class);
    }
}
