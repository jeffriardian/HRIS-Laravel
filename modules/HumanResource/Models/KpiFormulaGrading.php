<?php

namespace Modules\HumanResource\Models;

use App\Traits\Signature;
use Illuminate\Database\Eloquent\Model;

class KpiFormulaGrading extends Model
{
    use Signature;

    protected $fillable = ['kpi_formula_id', 'score_min', 'score_max', 'grade'];

    public function kpiFormula()
    {
        return $this->belongsTo(KpiFormula::class);
    }
}
