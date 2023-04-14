<?php

namespace Modules\HumanResource\Models;

use Modules\HumanResource\Models\InterrogationReport;
use Modules\HumanResource\Models\IncidentPenalty;
use Modules\HumanResource\Models\Memorandum;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;

class QueryReport extends Model
{
    public function queryInterrogation($request)
    {
        if($request->order_column){
            $data = InterrogationReport::orderBy($request->order_column, ($request->order_direction == 'true' ? 'DESC' : 'ASC'));
        }else{
            $data = InterrogationReport::orderBy('created_at', 'ASC');
        }
        
        $data->with(['incidentCategory']);

        if($request->interrogationStatus != ''){
            $data->where('is_active', $request->interrogationStatus);
        }
        
        if ($request->keyword != '') {
            $data->where(function($query) use ($request){
                $query->orWhereHas('incidentCategory', function ($query) use ($request){
                    $query->where('name', 'LIKE', '%' . $request->keyword . '%');
                });
            });
        }

        return $data;
    }

    public function queryIncidentPenalty($request)
    {
        if($request->order_column){
            $data = IncidentPenalty::orderBy($request->order_column, ($request->order_direction == 'true' ? 'DESC' : 'ASC'));
        }else{
            $data = IncidentPenalty::orderBy('created_at', 'ASC');
        }

        if($request->penaltyStatus != ''){
            $data->where('is_active', $request->penaltyStatus);
        }

        $data->with(['interrogationReportActor','interrogationReportActor.interrogationReport','interrogationReportActor.employee','interrogationReportActor.externalEmployee']);
        if ($request->keyword != '') {
            $data->where(function($query) use ($request){
                $query->orWhereHas('interrogationReportActor.employee', function ($query) use ($request){
                    $query->where('name', 'LIKE', '%' . $request->keyword . '%');
                });
                $query->orWhereHas('interrogationReportActor.externalEmployee', function ($query) use ($request){
                    $query->where('name', 'LIKE', '%' . $request->keyword . '%');
                });
            });
        }

        return $data;
    }
    public function queryMemorandum($request)
    {  
        if($request->order_column){
            $data = Memorandum::orderBy($request->order_column, ($request->order_direction == 'true' ? 'DESC' : 'ASC'));
        }else{
            $data = Memorandum::orderBy('created_at', 'ASC');
        }

        if($request->memorandumStatus != ''){
            $data->where('is_active', $request->memorandumStatus);
        }

        $data->with(['memorandumParameter','interrogationReportActor','interrogationReportActor.interrogationReport','interrogationReportActor.employee','interrogationReportActor.externalEmployee']);
        if ($request->keyword != '') {
            $data->where(function($query) use ($request){
                $query->orWhereHas('memorandumParameter', function ($query) use ($request){
                    $query->where('name', 'LIKE', '%' . $request->keyword . '%');
                });
                $query->orWhereHas('interrogationReportActor.employee', function ($query) use ($request){
                    $query->where('name', 'LIKE', '%' . $request->keyword . '%');
                });
                $query->orWhereHas('interrogationReportActor.externalEmployee', function ($query) use ($request){
                    $query->where('name', 'LIKE', '%' . $request->keyword . '%');
                });
            });
        }

        return $data;
    }
}
