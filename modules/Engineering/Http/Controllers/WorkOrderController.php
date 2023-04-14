<?php

namespace Modules\Engineering\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Notifications\Notify as ModelNotification;
use Modules\Engineering\Models\WorkOrder as Model;
use Modules\Engineering\Exports\WorkOrder as ModelExport;

use Excel;
use PDF;
use DB;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'))
        ->with(['workOrderAction']);
        if (request()->keyword != '') {
            $data = $data->where('code', 'LIKE', '%' . request()->keyword . '%');
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
        $data = Model::with(['machineGroup','machineGroup.parts','workOrderAction','serviceType','department'])->orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    public function getGeneratedCode($code){
        $identifier = str_replace('{id}', '', $code);
        $model = DB::table('work_orders')
                     ->selectRaw("MAX(REPLACE(code,'$identifier','')) as id")
                     ->where('code', 'like', $identifier.'%')
                     ->get()->first();

        $increment = $model ? ((int) $model->id + 1) : 1;
        return str_replace('{id}', $increment, $code);
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
            'work_order_action_id' => 'required',
            'source' => 'required',
            'type' => 'required',
            'code' => 'required|string|max:255',
        ]);

        $data = $request->except(['code']);
        $data['code'] = $this->getGeneratedCode($request->input('code'));
    
        Model::create($data);
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
        $data = Model::findOrFail($id);
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
            'work_order_action_id' => 'required',
            'source' => 'required',
            'type' => 'required',
            'code' => 'required|string|max:255',
        ]);
    
        $data = Model::findOrFail($id);
        $data->update($request->all());
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    /**
     * Export excel function.
     *
     * @return \Maatwebsite\Excel\Facades\Excel
     */
    public function exportExcel() 
    {
        $now = now();
        return Excel::download(new ModelExport, "work_order_list_{$now}.xlsx");
    }

    /**
     * Export excel function.
     *
     * @return \Barryvdh\DomPDF\Facade
     */
    public function exportPdf() 
    {
        $models = Model::orderBy('id', 'ASC')->get();
        $pdf = PDF::loadView('engineering::pdf.work-order', compact('models'));
        return $pdf->stream();
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
            $data = $data->where('code', 'LIKE', '%' . request()->keyword . '%');
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
