<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Http\Resources\DataCollection;

use App\Models\User;
use App\Notifications\Notify as ModelNotification;
use Modules\HumanResource\Models\Memorandum as Model;
use Modules\HumanResource\Models\InterrogationReport;

use DB;

class MemorandumController extends Controller
{
    public function index(){
    $data = Model::select('*')->with('employee','memorandumParameter','interrogationReportActor.employee','interrogationReportActor.externalEmployee','interrogationReportActor.interrogationReport');
    // $data = Model::select('*')->with('interrogationReportActor','interrogationReportActor.employee')->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
    
    $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

    if (request()->keyword != '') {
        $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%')
                    ->orWhere('memorandum_parameters.name', 'LIKE', '%' . request()->keyword . '%');
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
        // dd($request->all());
        $this->validate($request, [
            'employee_id' => 'required',
            'memorandum_parameter_id' => 'required',
            'start_date' => 'required',
            'description' => 'required',
        ]);

        DB::beginTransaction();
        try {

            $data = [
                'employee_id' => $request->employee_id,
                'memorandum_parameter_id' => $request->input('memorandum_parameter_id'),
                'start_date' => date('Y-m-d', strtotime($request->input('start_date'))),
                'end_date' => date('Y-m-d', strtotime($request->input('end_date'))),
                'description' => $request->input('description'),
                'is_active' => $request->input('is_active'),
            ];
            
            Model::create($data);
            
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Model::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function showInterrogation($id)
    {
        $data = InterrogationReport::with('incidentCategory', 'interrogationReportActors.employee', 'interrogationReportActors.externalEmployee', 'interrogationReportActors', 'interrogationReportImages')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            // 'employee_id' => 'required',
            // 'leave_category_id' => 'required',
        ]);
        
        $data = [
            'employee_id' => $request->input('employee_id'),
            'memorandum_parameter_id' => $request->input('memorandum_parameter_id'),
            'start_date' => date('Y-m-d', strtotime($request->input('start_date'))),
            'end_date' => date('Y-m-d', strtotime($request->input('end_date'))),
            'description' => $request->input('description'),
            'is_active' => $request->input('is_active'),
        ];
        Model::whereId($id)->update($data);

        // Model::create($data);

        // $data = Model::findOrFail($id);
        // $data->update($request->all());
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
