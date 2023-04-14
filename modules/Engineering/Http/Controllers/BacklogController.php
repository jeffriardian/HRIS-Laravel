<?php

namespace Modules\Engineering\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

use App\Models\User;
use App\Notifications\Notify as ModelNotification;
use Modules\Engineering\Models\Backlog as Model;
use Modules\Engineering\Exports\Backlog as ModelExport;

use Excel;
use PDF;
use DB;

class BacklogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'))
                    ->with(['jobCard','jobCard.workOrder','machine','failureType']);
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
            'job_card_id' => 'required',
            'machine_id' => 'required',
            'failure_type_id' => 'required',
            'estimation_repair_time' => 'required',
            'estimation_endurance_time' => 'required',

            'parts.*.part' => 'required',
            'parts.*.quantity' => 'required',
        ]);
    
        DB::beginTransaction();
        try {
            $backlog = Model::create($request->except('parts'));
            proccesRelationWithRequest(
                $backlog->parts(),
                $request->parts
            );
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Model::with(['parts'])->findOrFail($id);
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
            'job_card_id' => 'required',
            'machine_id' => 'required',
            'failure_type_id' => 'required',
            'estimation_repair_time' => 'required',
            'estimation_endurance_time' => 'required',

            'parts.*.part' => 'required',
            'parts.*.quantity' => 'required',
        ]);
    
        DB::beginTransaction();
        try {
            $backlog = Model::findOrFail($id);
            $backlog->update($request->except(['parts']));
            proccesRelationWithRequest(
                $backlog->parts(),
                $request->parts
            );
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_OK);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * Export excel function.
     *
     * @return \Maatwebsite\Excel\Facades\Excel
     */
    public function exportExcel() 
    {
        $now = now();
        return Excel::download(new ModelExport, "backlog_list_{$now}.xlsx");
    }

    /**
     * Export excel function.
     *
     * @return \Barryvdh\DomPDF\Facade
     */
    public function exportPdf() 
    {
        $models = Model::orderBy('id', 'ASC')->get();
        $pdf = PDF::loadView('engineering::pdf.backlog', compact('models'));
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
