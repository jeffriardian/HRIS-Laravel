<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\HistoryEmployeeStatus as Model;
use Modules\HumanResource\Models\Employee as Employee;

class HistoryEmployeeStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::select(['history_employee_statuses.*', 'employees.name as employee', 'contracts.name as contract',
                'periods.name as period'])
                ->join('employees', 'employees.id', '=', 'history_employee_statuses.employee_id')
                ->join('employee_statuses', 'employee_statuses.id', '=', 'history_employee_statuses.employee_status_id')
                ->join('contracts', 'contracts.id', '=', 'employee_statuses.contract_id')
                ->join('periods', 'periods.id', '=', 'employee_statuses.period_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%')
                         ->orWhere('employee_statuses.name', 'LIKE', '%' . request()->keyword . '%');
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
            'employee_status_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $employeestatusid = $request->input('employee_status_id');
            $startdate = date('Y-m-d', strtotime($request->input('start_date')));
            $end =  $this->getEndDate($employeestatusid, $startdate);

            if (!empty($end)){
                foreach($end as $end){

                    $data = [
                        'employee_id' => $request->input('employee_id'),
                        'employee_status_id' => $employeestatusid,
                        'start_date' =>  $startdate,
                        'end_date' => $end->end_date,
                        'is_active' => '1',
                    ];

                    Model::create($data);

                    $update_data = [
                        'employee_status_id' => $request->input('employee_status_id'),
                    ];

                    Employee::whereId($request->input('employee_id'))->update($update_data);

                }
            }

            // $data = [
            //     'employee_id' => $request->input('employee_id'),
            //     'employee_status_id' => $request->input('employee_status_id'),
            //     'start_date' => date('Y-m-d', strtotime($request->input('start_date'))),
            //     'end_date' => date('Y-m-d', strtotime($request->input('end_date'))),
            //     'is_active' => '1',
            // ];

            // Model::create($data);

            // $update_data = [
            //     'employee_status_id' => $request->input('employee_status_id'),
            // ];

            // Employee::whereId($request->input('employee_id'))->update($update_data);

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
            'employee_status_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $employeestatusid = $request->input('employee_status_id');
            $startdate = date('Y-m-d', strtotime($request->input('start_date')));
            $end =  $this->getEndDate($employeestatusid, $startdate);

            if (!empty($end)){
                foreach($end as $end){
                    $data = [
                        'employee_id' => $request->input('employee_id'),
                        'employee_status_id' => $employeestatusid,
                        'start_date' =>  $startdate,
                        'end_date' => $end->end_date,
                        'is_active' => '1',
                    ];

                    Model::whereId($id)->update($data);

                    $update_data = [
                        'employee_status_id' => $request->input('employee_status_id'),
                    ];

                    Employee::whereId($request->input('employee_id'))->update($update_data);
                }
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
