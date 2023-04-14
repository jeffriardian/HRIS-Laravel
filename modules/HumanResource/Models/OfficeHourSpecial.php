<?php

namespace Modules\HumanResource\Models;

use App\Traits\Signature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeHourSpecial extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['name', 'is_active'];

    // public function details()
    // {
    //     return $this->hasMany(OfficeHourSpecialDetail::class);
    // }

    public function details()
    {
        return $this->hasOne(OfficeHourSpecialDetail::class);
    }
}
