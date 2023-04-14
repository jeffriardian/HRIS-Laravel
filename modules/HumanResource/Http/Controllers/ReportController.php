<?php

namespace Modules\HumanResource\Http\Controllers;


use Illuminate\Support\Carbon;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\DataCollection;
use Illuminate\Routing\Controller;

use Modules\HumanResource\Models\Employee;
use Modules\HumanResource\Models\InterrogationReport;
use Modules\HumanResource\Models\Memorandum;
use Modules\HumanResource\Models\IncidentPenalty;

use Modules\HumanResource\Models\QueryReport;

use Excel;
use PDF;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function interrogation()
    {

        $data = new QueryReport;
        $data = $data->queryInterrogation(request());

        return new DataCollection($data->paginate(request()->per_page));
    }

    public function incidentPenalty()
    {

        $data = new QueryReport;
        $data = $data->queryIncidentPenalty(request());

        return new DataCollection($data->paginate(request()->per_page));
    }

    public function memorandum()
    {

        $data = new QueryReport;
        $data = $data->queryMemorandum(request());

        return new DataCollection($data->paginate(request()->per_page));
    }

    public function showInterrogation($id)
    {
        $data = InterrogationReport::with('incidentCategory', 'interrogationReportActors.employee', 'interrogationReportActors.externalEmployee', 'interrogationReportActors', 'interrogationReportImages')->findOrFail($id);
        foreach($data->interrogationReportActors as $value){
            if($value['interrogation_report_actor_type_id'] == 2){
                if($value['employee_id'] == 0){
                    $id = $value['external_employee_id'];
                    $status = "External";
                }else{
                    $status = "Internal";
                    $id = $value['employee_id'];
                }
            }
        }
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function getMemo($id, $status){
            if($status == "External"){
                $data = Memorandum::with(['interrogationReportActor','memorandumParameter'])->where(function($query) use($id){
                    $query->whereHas('interrogationReportActor', function($q) use($id){
                        $q->where('external_employee_id', $id);
                    });
                })->get();
                return response()->json(['status' => 'success', 'data' => $data, 'penalty' => $this->getPenalty($id, $status)], Response::HTTP_OK);
            }else{
                $data = Memorandum::with(['interrogationReportActor','memorandumParameter'])->where(function($query) use($id){
                    $query->whereHas('interrogationReportActor', function($q) use($id){
                        $q->where('employee_id', $id);
                    });
                })->get();
                return response()->json(['status' => 'success', 'data' => $data, 'penalty' => $this->getPenalty($id, $status)], Response::HTTP_OK);
            }
    }

    public function getPenalty($id, $status){
        if($status == "Internal"){
            $data = IncidentPenalty::with(['interrogationReportActor','incidentPenaltyDetails'])->where(function($query) use($id){
                $query->whereHas('interrogationReportActor', function($q) use($id){
                    $q->where('employee_id', $id);
                });
            })->get();
            return $data;
        }else{
            $data = IncidentPenalty::with(['interrogationReportActor','incidentPenaltyDetails'])->where(function($query) use($id){
                $query->whereHas('interrogationReportActor', function($q) use($id){
                    $q->where('external_employee_id', $id);
                });
            })->get();
            return $data;
        }
    }




}
