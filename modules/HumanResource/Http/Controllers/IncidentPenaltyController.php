<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\IncidentPenalty as Model;
use Modules\HumanResource\Models\InterrogationReport as Interrogation;

use DB;

class IncidentPenaltyController extends Controller
{
    public function index()
    {
        $data = Model::select('*')->with('interrogationReportActor','interrogationReportActor.interrogationReport','interrogationReportActor.employee','interrogationReportActor.externalEmployee')->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
            // ->join('employees', 'employees.id', '=', 'interrogation_reports.witnessess');

        // $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%')
                        ->orWhere('interrogation_reports.name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'employee_id' => 'required',
        //     'date_of_incident' => 'required',
        //     'description' => 'required',
        //     'lost_cost' => 'required',
        // ]);

        // DB::beginTransaction();
        // try {
        //     $data = [
        //         'employee_id' => $request->input('employee_id'),
        //         'date_of_incident' => date('Y-m-d', strtotime($request->input('date_of_incident'))),
        //         'description' => $request->input('description'),
        //         'lost_cost' => $request->input('lost_cost'),
        //     ];

        //     Model::create($data);

        //     DB::commit();
        //     return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        // }catch (\Exception $e) {
        //     DB::rollback();
        //     return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        // }
    }

    public function show($id)
    {
        $data = Interrogation::with('incident')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function showInterrogation($id)
    {
        $data = Interrogation::with('incidentCategory', 'interrogationReportActors.employee', 'interrogationReportActors.externalEmployee','interrogationReportActors', 'interrogationReportImages')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'penalty' => 'required',
            'installment_count' => 'required',
        ]);

        $data = [
            'interrogation_report_id' => $id,
            'penalty' => $request->input('penalty'),
            'installment_count' => $request->input('installment_count'),
        ];

        Model::create($data);

        // $data = [
        //     'employee_id' => $request->input('employee_id'),
        //     'date_of_incident' => date('Y-m-d', strtotime($request->input('date_of_incident'))),
        //     'description' => $request->input('description'),
        //     'lost_cost' => $request->input('lost_cost'),
        // ];

        // Model::whereId($id)->update($data);

        // Model::create($data);

        // $data = Model::findOrFail($id);
        // $data->update($request->all());
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $data = Model::findOrFail($id);
        $data->delete();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    public function forceDelete($id)
    {
        $data = Model::findOrFail($id);
        $data->forceDelete();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    public function trash()
    {
        $data = Model::onlyTrashed()->orderBy('created_at', 'DESC');
        if (request()->keyword != '') {
            $data = $data->where('module', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function restore($id)
    {
        $data = Model::findOrFail($id);
        $data->restore();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }
}
