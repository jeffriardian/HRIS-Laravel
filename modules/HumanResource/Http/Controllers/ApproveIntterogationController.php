<?php

namespace Modules\HumanResource\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\HumanResource\Models\InterrogationReport as Model;
use Modules\HumanResource\Models\InterrogationReportActor;
use Modules\HumanResource\Models\IncidentPenalty;
use Modules\HumanResource\Models\IncidentPenaltyDetail;
use Modules\HumanResource\Models\Memorandum;
use Modules\HumanResource\Models\InterrogationReportImage;

class ApproveIntterogationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // $data = Model::select(['interrogation_reports.*', 'employees.name as name'])
        //     ->join('employees', 'employees.id', '=', 'interrogation_reports.employee_id');

        // $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        $data = Model::with('incidentCategory')->where('is_active', 1)->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $search = request()->keyword;
            $data->whereHas('incidentCategory', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            })->orWhere('date_of_incident', 'LIKE', '%' . $search . '%')->orWhere('lost_cost', 'LIKE', '%' . $search . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data = Model::with('typeable','incidentCategory', 'interrogationReportActors.employee','interrogationReportActors.memorandums','interrogationReportActors.externalEmployee', 'interrogationReportActors', 'interrogationReportImages','interrogationReportActors.incidentPenalties')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function showMemorandum(Request $request){
        $id = $request->id;
        if($request->status == "Internal"){
            $data = Memorandum::with(['interrogationReportActor','memorandumParameter'])->where(function($query) use($id){
                $query->whereHas('interrogationReportActor', function($q) use($id){
                    $q->where('employee_id', $id);
                });
            })->get();
            return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
        }else{
            $data = Memorandum::with(['interrogationReportActor','memorandumParameter'])->where(function($query) use($id){
                $query->whereHas('interrogationReportActor', function($q) use($id){
                    $q->where('external_employee_id', $id);
                });
            })->get();
            return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
        }
    }

    public function showPenalty(Request $request){
        $id = $request->id;
        if($request->status == "Internal"){
            $data = IncidentPenalty::with(['interrogationReportActor',])->where(function($query) use($id){
                $query->whereHas('interrogationReportActor', function($q) use($id){
                    $q->where('employee_id', $id);
                });
            })->get();
            return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
        }else{
            $data = IncidentPenalty::with(['interrogationReportActor'])->where(function($query) use($id){
                $query->whereHas('interrogationReportActor', function($q) use($id){
                    $q->where('external_employee_id', $id);
                });
            })->get();
            return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        
        // if($request->approve == "Accepted"){
        //     $this->validate($request, [
        //         'interrogation_report_actors.*.penalty' => 'required|numeric|max:'.$request->lost_cost,
        //         'interrogation_report_actors.*.installment_count' => 'required|numeric|min:1|max:24',
        //     ]);
        //     // if(isset($request->statusMemorandum) && $request->statusMemorandum == "Yes"){
        //         $this->validate($request, [
        //             'interrogation_report_actors.*.memorandum_parameter_id' => 'required',
        //             'interrogation_report_actors.*.start_date' => 'required',
        //         ]); 
            
        // }

        DB::beginTransaction();
        try {
            
            if($request->approve == "Accepted"){
                if($request->lost_cost > 0 && $request->approve == "Accepted"){
                    // dd($request->all());
                    foreach($request->suspects as $actor){

                            if($actor['statusPenalty'] == true){
                                $data = [
                                    'interrogation_report_actor_id' => $actor['id'],
                                    'penalty' => $actor['penalty'],
                                    'installment_count' => $actor['installment_count'],
                                    'is_active' => 1
                                ];

                                $incident = IncidentPenalty::create($data);
                                foreach($actor['penalties'] as $penalties){
                                    $data3 = [
                                        'incident_penalty_id' => $incident->id,
                                        'amount' => $penalties['amount'],
                                        'precentage' => $penalties['percentage'],
                                        'due_date' => $penalties['due_date'],
                                        'is_active' => 1
                                    ];
                                    IncidentPenaltyDetail::create($data3);
                                }
                            }
                            
                            if($actor['statusMemorandum'] == true){
                                $data2 = [
                                    'interrogation_report_actor_id' => $actor['id'],
                                    'memorandum_parameter_id' => $actor['parameter_id'],
                                    'start_date' => $actor['start_date'],
                                    'end_date' => $actor['end_date'],
                                    'description' => $actor['memoDescription'],
                                    'is_active' => 1
                                ];
                                Memorandum::create($data2);
                            }
                    }
                }else{
                    foreach($request->suspects as $memo){
                        if($memo['statusMemorandum'] == true){
                            // dd($memo['statusMemorandum']);
                            $data2 = [
                                'interrogation_report_actor_id' => $memo['id'],
                                'memorandum_parameter_id' => $memo['parameter_id'],
                                'start_date' => $memo['start_date'],
                                'end_date' => $memo['end_date'],
                                'description' => $memo['memoDescription'],
                                'is_active' => 1
                            ];
                            Memorandum::create($data2);
                        }
                    }   
                }

                $data2 = [
                    'is_active' => 0
                ];
    
                Model::whereId($request->id)->update($data2);
            }else if($request->approve == "Rejected"){
                $data2 = [
                    'is_active' => 2
                ];
    
                Model::whereId($request->id)->update($data2);
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
