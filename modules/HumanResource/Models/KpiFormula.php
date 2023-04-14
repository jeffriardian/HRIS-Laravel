<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class KpiFormula extends Model
{
    protected $fillable = [];

    public function kpiGrades()
    {
        return $this->hasMany(KpiFormulaGrading::class);
    }
}
