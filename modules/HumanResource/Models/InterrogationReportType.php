<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\Signature;

class InterrogationReportType extends Model
{
    use SoftDeletes, Signature;

    protected $fillable = ['name'];

    public function interrogationReports(){
        return $this->hasMany(InterrogationReport::class);
    }
}
