<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\HistoryEmployeePosition as Model;
use Modules\HumanResource\Models\Employee as Employee;

class HistoryEmployeePositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::select(['history_employee_positions.*', 'employees.name as employee', 'departments.name as department',
                'positions.name as position'])
                ->join('employees', 'employees.id', '=', 'history_employee_positions.employee_id')
                ->join('departments', 'departments.id', '=', 'history_employee_positions.department_id')
                ->join('positions', 'positions.id', '=', 'history_employee_positions.position_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%')
                         ->orWhere('departments.name', 'LIKE', '%' . request()->keyword . '%')
                         ->orWhere('positions.name', 'LIKE', '%' . request()->keyword . '%');
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
            'employee_id' => 'required',
            'department_id' => 'required',
            'position_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $file = $request->file('file');
            $fileName = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
            // dd($request->candidate_id);
            $file->storeAs('/public/images/employee-photos', $fileName);
            $data = [
                'employee_id' => $request->employee_id,
                'department_id' => $request->department_id,
                'position_id' => $request->position_id,
                'start_date' => $request->start_date,
                'file' => $fileName,
                'is_active' => '1'
            ];

            Model::create($data);

            $update_data = [
                'department_id' => $request->input('department_id'),
                'position_id' => $request->input('position_id'),
            ];

            Employee::whereId($request->input('employee_id'))->update($update_data);

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
            'employee_id' => 'required',
            'department_id' => 'required',
            'position_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
            // dd($request->candidate_id);
            $file->storeAs('/public/images/employee-photos', $fileName);
            $data = [
                'department_id' => $request->input('department_id'),
                'position_id' => $request->input('position_id'),
                'start_date' => $request->start_date,
                'file' => $fileName,
                'is_active' => '1'
            ];

            Model::whereId($id)->update($data);

            $update_data = [
                'department_id' => $request->input('department_id'),
                'position_id' => $request->input('position_id'),
            ];

            Employee::whereId($request->input('employee_id'))->update($update_data);

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }else{
            $data = [
                'department_id' => $request->input('department_id'),
                'position_id' => $request->input('position_id'),
                'start_date' => $request->start_date,
                'is_active' => '1'
            ];

            Model::whereId($id)->update($data);

            $update_data = [
                'department_id' => $request->input('department_id'),
                'position_id' => $request->input('position_id'),
            ];

            Employee::whereId($request->input('employee_id'))->update($update_data);

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
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

    public function getEndDate($employeestatusid, $startdate)
    {
        $end = DB::select('SELECT a.id, (SELECT time_period FROM periods WHERE id = a.period_id) AS time_period,
        (SELECT DATE_ADD(:startdate, interval (SELECT time_period FROM periods WHERE id = a.period_id) month)) AS end_date
        FROM employee_statuses a WHERE a.id = :employeestatusid',
        array('employeestatusid'=>$employeestatusid, 'startdate'=>$startdate));

        return $end;
    }
}
