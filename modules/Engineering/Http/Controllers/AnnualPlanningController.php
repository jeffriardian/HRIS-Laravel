<?php

namespace Modules\Engineering\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Notifications\Notify as ModelNotification;
use Modules\Engineering\Imports\AnnualPlanning as ModelImport;
use Modules\Engineering\Models\EngineeringAnnualPlanning as Model;
use Modules\Engineering\Models\EngineeringAnnualPlanningDetail as ModelDetail;

use Excel;
use PDF;
use DB;

class AnnualPlanningController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::orderBy('created_at', 'DESC');
        if (request()->keyword != '') {
            $data = $data->where('name', 'LIKE', '%' . request()->keyword . '%');
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'year' => 'required|integer',
            'file' => 'required|file'
        ]);

        if ($request->hasFile('file')) {
            $exelFile = $request->file('file');
            Excel::import(new ModelImport($request->input('year')), $exelFile);
        }

        return response()->json(['status' => 'success'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Model::with(['details','details.machine'=> function ($query) {
            $query->select(['id', 'machine_group_id', 'code']);
        },'details.machine.machineGroup'=> function ($query) {
            $query->select(['id', 'name']);
        }])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function annual($id){
        $models = ModelDetail::select(['engineering_annual_planning_details.*', 'machines.code as machine_code', 'machine_groups.name as machine_group'])
                ->join('machines', 'machines.id', '=', 'engineering_annual_planning_details.machine_id')
                ->join('machine_groups', 'machine_groups.id', '=', 'machines.machine_group_id')
        ->where('engineering_annual_planning_id', $id)
        ->get();

        $result = [];
        $month = [];
        foreach($models as $key => $model){
            for ($m = 1; $m <= 12; $m++) {
                for ($w = 1; $w <= 4; $w++) {
                    $month[$m][$w] = $model->{$m.'_'.$w};
                }
            }

            $result[] = [
                'machine_id' => $model->machine_id,
                'machine_code' => $model->machine_code,
                'machine_group' => $model->machine_group,
                'month' => $month,
            ];
        }

        return response()->json(['status' => 'success', 'data' => $result], Response::HTTP_OK);
    }

    public function monthly($id){
        $models = ModelDetail::select(['engineering_annual_planning_details.*', 'machines.code as machine_code', 'machine_groups.name as machine_group'])
                ->join('machines', 'machines.id', '=', 'engineering_annual_planning_details.machine_id')
                ->join('machine_groups', 'machine_groups.id', '=', 'machines.machine_group_id')
        ->where('engineering_annual_planning_id', $id)
        ->get();

        $result = [];
        $month = [];
        foreach($models as $key => $model){
            for ($m = 1; $m <= 12; $m++) {
                for ($w = 1; $w <= 4; $w++) {
                    $month[$m][$w] = $model->{$m.'_'.$w};
                }
            }

            $result[] = [
                'machine_id' => $model->machine_id,
                'machine_code' => $model->machine_code,
                'machine_group' => $model->machine_group,
                'month' => $month,
            ];
        }

        return response()->json(['status' => 'success', 'data' => $result], Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     * https://stackoverflow.com/questions/52000164/laravel-eloquent-nested-group-by-its-relation
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function view($id)
    {
        $annuals = Model::with(['details' => function ($query) {
            $query->select(['id', 'engineering_annual_planning_id', 'machine_id', 'type_service_id', 'month', 'week']);
            $query->orderBy('month', 'ASC');
            $query->orderBy('week', 'ASC');
        }, 'details.machine'=> function ($query) {
            $query->select(['id', 'code']);
        }, 'details.typeService'=> function ($query) {
            $query->select(['id', 'code', 'name']);
        }])->get()->map(function ($models) {
            return $models->details->groupBy('machine_id');
        });

        //dd($annuals); exit;

        $data = [];
        foreach($annuals as $key => $annual){
            foreach($annual as $model){
                $data[] = [
                    'machine_id' => $model['machine_id']
                ];
            }
        }

        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'year' => 'required|integer',
        ]);
    
        $data = Model::findOrFail($id);
        $data->update($request->all());
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Model::findOrFail($id);
        $data->delete();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function forceDelete($id)
    {
        $data = Model::findOrFail($id);
        $data->forceDelete();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function trash()
    {
        $data = Model::onlyTrashed()->orderBy('created_at', 'DESC');
        if (request()->keyword != '') {
            $data = $data->where('module', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        $data = Model::findOrFail($id);
        $data->restore();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }
}
