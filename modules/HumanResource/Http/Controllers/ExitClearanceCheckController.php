<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Models\ExitClearance as Model;
use Modules\HumanResource\Models\ExitClearanceCheck;

use Modules\HumanResource\Models\Cooperative;
use Modules\HumanResource\Models\ExitClearanceParameter;
use Modules\HumanResource\Models\InventoryEmployee;

class ExitClearanceCheckController extends Controller
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

    public function loadAll($id)
    {
        // $data = Model::orderBy('id', 'ASC');

        $data = ExitClearanceCheck::select(['exit_clearance_checks.*'])
            ->where('exit_clearance_checks.employee_id', '=', $id);

        return new DataCollection($data->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $noexit = $this->getExitClearanceNumber();
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
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['status' => 'error', 'data' => $th->getMessage()]);
        }

        // $employeeid = $request->input('employee_id');

        // $cekdata = $this->checkData($employeeid);
        // if (!empty($cekdata)) {
        //     $this->deleteData($employeeid);

        //     $parametercode = $this->getParameterCode();

        //     if (!empty($parametercode)) {
        //         foreach ($parametercode as $parametercode) {
        //             $name = $parametercode->name;

        //             if ($name == 'Inventory') {
        //                 $inventory = $this->checkInventory($employeeid);
        //                 foreach ($inventory as $inventory) {
        //                     $jumlah = $inventory->jumlah;

        //                     if ($jumlah == '0') {
        //                         $data = [
        //                             'employee_id' => $employeeid,
        //                             'exit_clearance_parameter' => $parametercode->id,
        //                             'status' => '1',
        //                         ];

        //                         ExitClearanceCheck::create($data);
        //                     } else
        //                     if ($jumlah != '0') {
        //                         $data = [
        //                             'employee_id' => $employeeid,
        //                             'exit_clearance_parameter' => $parametercode->id,
        //                             'status' => '0',
        //                         ];

        //                         ExitClearanceCheck::create($data);
        //                     }
        //                 }
        //             } else
        //             if ($name == 'Cooperative') {
        //                 $cooperative = $this->checkCooperative($employeeid);
        //                 foreach ($cooperative as $cooperative) {
        //                     $jumlah = $cooperative->jumlah;

        //                     if ($jumlah == '0') {
        //                         $data = [
        //                             'employee_id' => $employeeid,
        //                             'exit_clearance_parameter' => $parametercode->id,
        //                             'status' => '1',
        //                         ];

        //                         ExitClearanceCheck::create($data);
        //                     } else
        //                     if ($jumlah != '0') {
        //                         $data = [
        //                             'employee_id' => $employeeid,
        //                             'exit_clearance_parameter' => $parametercode->id,
        //                             'status' => '0',
        //                         ];

        //                         ExitClearanceCheck::create($data);
        //                     }
        //                 }
        //             }
        //         }
        //     }

        //     $id = $request->input('employee_id');
        //     $this->loadAll($id);
        // } else
        // if (empty($cekdata)) {
        //     $parametercode = $this->getParameterCode();

        //     if (!empty($parametercode)) {
        //         foreach ($parametercode as $parametercode) {
        //             $name = $parametercode->name;

        //             if ($name == 'Inventory') {
        //                 $inventory = $this->checkInventory($employeeid);
        //                 foreach ($inventory as $inventory) {
        //                     $jumlah = $inventory->jumlah;

        //                     if ($jumlah == '0') {
        //                         $data = [
        //                             'employee_id' => $employeeid,
        //                             'exit_clearance_parameter' => $parametercode->id,
        //                             'status' => '1',
        //                         ];

        //                         ExitClearanceCheck::create($data);
        //                     } else
        //                     if ($jumlah != '0') {
        //                         $data = [
        //                             'employee_id' => $employeeid,
        //                             'exit_clearance_parameter' => $parametercode->id,
        //                             'status' => '0',
        //                         ];

        //                         ExitClearanceCheck::create($data);
        //                     }
        //                 }
        //             } else
        //             if ($name == 'Cooperative') {
        //                 $cooperative = $this->checkCooperative($employeeid);
        //                 foreach ($cooperative as $cooperative) {
        //                     $jumlah = $cooperative->jumlah;

        //                     if ($jumlah == '0') {
        //                         $data = [
        //                             'employee_id' => $employeeid,
        //                             'exit_clearance_parameter' => $parametercode->id,
        //                             'status' => '1',
        //                         ];

        //                         ExitClearanceCheck::create($data);
        //                     } else
        //                     if ($jumlah != '0') {
        //                         $data = [
        //                             'employee_id' => $employeeid,
        //                             'exit_clearance_parameter' => $parametercode->id,
        //                             'status' => '0',
        //                         ];

        //                         ExitClearanceCheck::create($data);
        //                     }
        //                 }
        //             }
        //         }
        //     }
        //     $id = $request->input('employee_id');
        //     $this->loadAll($id);
        // }
    }

    public function show($id)
    {
        $data = Model::with('parameters')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        // 
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

    public function GetExitClearanceNumber()
    {
        $noexit = DB::select('select (MAX(id)+1) as id, concat("EXT", "/", "SMM", "/",YEAR(NOW()),"/",LPAD(((select coalesce(MAX(id),0)
        from exit_clearances where YEAR(created_at) = YEAR(NOW()))+1),"5","0")) AS no from exit_clearances');
        return $noexit;
    }

    public function getParameterCode()
    {
        $parametercode = DB::select('select id, name from exit_clearance_parameters');

        return $parametercode;
    }

    public function checkInventory($employeeid)
    {
        $inventory = DB::select(
            'SELECT COUNT(id) AS jumlah FROM inventory_employees WHERE employee_id = :employeeid AND STATUS = :status',
            array('employeeid' => $employeeid, 'status' => 'Used')
        );

        return $inventory;
    }

    public function checkCooperative($employeeid)
    {
        $cooperative = DB::select(
            'SELECT COUNT(id) AS jumlah FROM cooperatives WHERE employee_id = :employeeid AND loan_balance != :balance',
            array('employeeid' => $employeeid, 'balance' => '0')
        );

        return $cooperative;
    }

    public function checkData($employeeid)
    {
        $cekdata = DB::select(
            'SELECT COUNT(id) AS jumlah FROM exit_clearance_checks WHERE employee_id = :employeeid',
            array('employeeid' => $employeeid)
        );

        return $cekdata;
    }

    public function deleteData($employeeid)
    {
        $deletedata = DB::select(
            'delete FROM exit_clearance_checks WHERE employee_id = :employeeid',
            array('employeeid' => $employeeid)
        );

        return $deletedata;
    }

    public function exitClearanceCheck(Request $request)
    {
        $resultCheck = [];

        $checkCooperativeEmployee = Cooperative::where('employee_id', $request->employee_id)->sum('loan_balance');

        array_push($resultCheck, [
            'parameter' => 'Loan Cooperative',
            'status' => $checkCooperativeEmployee > 0 ? 0 : 1
        ]);

        $checkInventoryEmployee = InventoryEmployee::where('employee_id', $request->employee_id)->where('status', 'Used')->get();

        array_push($resultCheck, [
            'parameter' => 'Inventory and Asset',
            'status' => count($checkInventoryEmployee) > 0 ? 0 : 1
        ]);

        return response()->json($resultCheck);
    }
}
