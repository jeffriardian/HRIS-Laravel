<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class ExternalEmployee extends Model
{
    use SoftDeletes, Signature;
    protected $fillable = ['identity_card_number', 'license_number', 'name', 'phone_number', 'is_active'];

    public function interrogationReportActors()
    {
        return $this->hasMany(InterrogationReportActor::class);
    }
}
