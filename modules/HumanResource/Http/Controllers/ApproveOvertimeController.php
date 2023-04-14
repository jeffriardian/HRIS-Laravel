<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\Overtime as Model;
use Modules\HumanResource\Models\OvertimeEmployee as OvertimeEmployee;

use DB;

class ApproveOvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::select(['overtime_employees.id', 'overtimes.start_time', 'overtimes.end_time', 'overtimes.description',
                'overtimes.created_at', 'employees.name as employee', 'approval_statuses.name as approval'])
                ->join('overtime_employees', 'overtimes.id', '=', 'overtime_employees.overtime_id')
                ->join('employees', 'employees.id', '=', 'overtime_employees.employee_id')
                ->join('approval_statuses', 'approval_statuses.id', '=', 'overtime_employees.approval_status_id')
                ->where('approval_statuses.name', '=', 'Created User');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%');
                        //  ->orWhere('training_types.name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    public function show($id)
    {
        $data = OvertimeEmployee::with('overtime','employee')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        // $data = OvertimeEmployee::findOrFail($id);
        // $data->delete();

        if (!empty($id)) {

            $approve_overtime = [
                'approval_status_id' => '2',
                'updated_at' => now()
            ];

            OvertimeEmployee::whereId($id)->update($approve_overtime);
        }
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
