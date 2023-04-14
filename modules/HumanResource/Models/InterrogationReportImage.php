<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;

class InterrogationReportImage extends Model
{
    protected $fillable = ['interrogation_report_id', 'image', 'note'];

    public function interrogationReport()
    {
        return $this->belongsTo(InterrogationReport::class);
    }
}
