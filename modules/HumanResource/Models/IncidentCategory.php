<?php

namespace Modules\HumanResource\Models;

use App\Traits\Signature;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IncidentCategory extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['name'];

    public function interrogationReports()
    {
        return $this->hasMany(InterrogationReport::class);
    }
}
