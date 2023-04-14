<?php

namespace Modules\HumanResource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use App\Traits\Signature;
use Spatie\Activitylog\Traits\LogsActivity;

class InterrogationReport extends Model
{
    use SoftDeletes, Signature, LogsActivity;

    protected $fillable = ['code','incident_category_id', 'date_of_incident', 'incident_chronologic', 'lost_cost', 'interrogation_report_type_id', 'typeable_type', 'typeable_id', 'is_active'];

    protected static $logName = "Interrogation Report";
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;

    public function incidentCategory()
    {
        return $this->belongsTo(IncidentCategory::class);
    }

    public function interrogationReportActors()
    {
        return $this->hasMany(InterrogationReportActor::class);
    }

    public function interrogationReportImages()
    {
        return $this->hasMany(InterrogationReportImage::class);
    }

    public function interrogationReportType(){
        return $this->belongsTo(InterrogationReportType::class);
    }

    public function typeable(){
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function($detail) {
            $timestamp = Carbon::now()->format('YmdHs');
            $detail->code = "IR/{$timestamp}";
            return true;
        });

    }
}
