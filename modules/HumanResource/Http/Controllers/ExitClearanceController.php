<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Models\Cooperative;
use Modules\HumanResource\Models\ExitClearance as Model;
use Modules\HumanResource\Models\ExitClearanceCheck;
use Modules\HumanResource\Models\ExitClearanceParameter;
// use Modules\HumanResource\Models\ExitClearanceParameterCheck;
use Modules\HumanResource\Models\InventoryEmployee;

class ExitClearanceController extends Controller
{
    public function index()
    {
        $data = Model::select(['exit_clearances.*', 'employees.name as name'])
            ->join('employees', 'employees.id', '=', 'exit_clearances.employee_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%');
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
        // dd($request->all());
        $this->validate($request, [
            'employee_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $checkEmployee = Model::where('employee_id', $request->employee_id)->count();
            if ($checkEmployee == 0) {
                $noexit = $this->getExitClearanceNumber();

                if (!empty($noexit)) {
                    foreach ($noexit as $no) {
                        $data = [
                            'employee_id' => $request->employee_id,
                            'exit_clearance_number' => $no->no,
                            'is_active' => 1,
                        ];

                        $exitclearance = Model::create($data);
                        $getExitParameters = ExitClearanceParameter::get();

                        foreach ($getExitParameters as $key) {
                            ExitClearanceCheck::create([
                                'exit_clearance_id' => $exitclearance->id,
                                'exit_clearance_parameter_id' => $key->id,
                                'status' => 1
                            ]);
                        }
                    }
                }
            }


            // if (!empty($noexit)) {
            //     foreach ($noexit as $noexit) {

            //         $data = [
            //             'employee_id' => $request->input('employee_id'),
            //             'exit_clearance_number' => $noexit->no,
            //             'is_active' => '0',
            //         ];

            //         $exitclearance = Model::create($data);

            //         foreach ($request->parameters as $parameters) {

            //             if (!empty($parameters['exit_clearance_parameter_id']) or ($parameters['exit_clearance_parameter_id'] != NULL)) {
            //                 $data = [
            //                     'exit_clearance_id' => $exitclearance->id,
            //                     'exit_clearance_parameter_id' => $parameters['exit_clearance_parameter_id'],
            //                     'is_active' => $parameters['is_active'],
            //                 ];

            //                 ExitClearanceParameterCheck::create($data);
            //             }
            //         }

            //         $id = $request->input('employee_id');
            //         $cekinventory = $this->checkInventory($id);

            //         foreach ($cekinventory as $cekinventory) {
            //             $jumlah = $cekinventory->jumlah;

            //             $parametercode = $this->getParameterCode();

            //             foreach ($parametercode as $parametercode) {
            //                 if ($jumlah > 0) {
            //                     $data = [
            //                         'exit_clearance_id' => $exitclearance->id,
            //                         'exit_clearance_parameter_id' => $parametercode->id,
            //                         'is_active' => '0',
            //                     ];

            //                     ExitClearanceParameterCheck::create($data);
            //                 } else {
            //                     $data = [
            //                         'exit_clearance_id' => $exitclearance->id,
            //                         'exit_clearance_parameter_id' => $parametercode->id,
            //                         'is_active' => '1',
            //                     ];

            //                     ExitClearanceParameterCheck::create($data);
            //                 }
            //             }
            //         }

            //         $eclearid = $exitclearance->id;
            //         $cekparameter = $this->checkParameter($eclearid);

            //         foreach ($cekparameter as $cekparameter) {
            //             $jumlah = $cekparameter->jumlah;

            //             if ($jumlah > 0) {
            //                 $update_status = [
            //                     'is_active'        => '0',
            //                 ];

            //                 Model::whereId($eclearid)->update($update_status);
            //             } else {
            //                 $update_status = [
            //                     'is_active'        => '1',
            //                 ];

            //                 Model::whereId($eclearid)->update($update_status);
            //             }
            //         }
            //     }
            // }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        // dd($id);
        $data = Model::with('parameters')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
        // $data = ExitClearanceParameterCheck::with('clearances','parameters')->findOrFail($id);
        // return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        // foreach ($request->parameters as $parameters) {
        //     if (!empty($parameters['exit_clearance_parameter_id']) or ($parameters['exit_clearance_parameter_id'] != NULL)) {
        //         $parameter = $parameters['exit_clearance_parameter_id'];

        //         $codes = $this->getCode($id, $parameter);

        //         foreach ($codes as $codes) {
        //             $code = $codes->id;

        //             $data = [
        //                 'is_active' => $parameters['is_active'],
        //             ];

        //             ExitClearanceParameterCheck::whereId($code)->update($data);
        //         }
        //     }
        // }

        // $eclearid = $id;
        // $cekparameter = $this->checkParameter($eclearid);

        // foreach ($cekparameter as $cekparameter) {
        //     $jumlah = $cekparameter->jumlah;

        //     if ($jumlah > 0) {
        //         $update_status = [
        //             'is_active'        => '0',
        //         ];

        //         Model::whereId($eclearid)->update($update_status);
        //     } else {
        //         $update_status = [
        //             'is_active'        => '1',
        //         ];

        //         Model::whereId($eclearid)->update($update_status);
        //     }
        // }

        // return response()->json(['status' => 'success'], Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $data = Model::findOrFail($id);
        $data->delete();
        ExitClearanceCheck::whereIn('exit_clearance_id', [$id])->delete();
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

    public function GetExitClearanceNumber()
    {
        $noexit = DB::select('select (MAX(id)+1) as id, concat("EXT", "/", "SMM", "/",YEAR(NOW()),"/",LPAD(((select coalesce(MAX(id),0)
        from exit_clearances where YEAR(created_at) = YEAR(NOW()))+1),"5","0")) AS no from exit_clearances');

        return $noexit;
    }

    public function checkInventory($id)
    {
        $cekinventory = DB::select(
            'select count(id) as jumlah from inventory_employees where employee_id = :id and status = :status',
            array('id' => $id, 'status' => 'Used')
        );

        return $cekinventory;
    }

    public function checkParameter($eclearid)
    {
        $cekinventory = DB::select(
            'select count(id) as jumlah from exit_clearance_parameter_checks where exit_clearance_id = :eclearid and is_active = :status',
            array('eclearid' => $eclearid, 'status' => '0')
        );

        return $cekinventory;
    }

    public function getParameterCode()
    {
        $parametercode = DB::select(
            'select id from exit_clearance_parameters where name = :name',
            array('name' => 'Inventory')
        );

        return $parametercode;
    }

    public function getCode($id, $parameter)
    {
        $codes = DB::select(
            'select id from exit_clearance_parameter_checks
        where exit_clearance_id = :id and exit_clearance_parameter_id = :parameter',
            array('id' => $id, 'parameter' => $parameter)
        );

        return $codes;
    }

    public function exitClearanceCheck(Request $request)
    {
        $resultCheck = [];

        $checkCooperativeEmployee = Cooperative::where('employee_id', $request->employee_id)->sum('loan_balance');

        array_push($resultCheck, [
            'parameter' => 'Loan Cooperative',
            'status' => $checkCooperativeEmployee > 0 ? 0 : 1,
            'information' => $checkCooperativeEmployee
        ]);

        $checkInventoryEmployee = InventoryEmployee::with('inventory.inventoryCategory')->where('employee_id', $request->employee_id)->where('status', 'Used')->get();

        array_push($resultCheck, [
            'parameter' => 'Inventory and Asset',
            'status' => count($checkInventoryEmployee) > 0 ? 0 : 1,
            'information' => $checkInventoryEmployee
        ]);

        return response()->json($resultCheck);
    }
}
