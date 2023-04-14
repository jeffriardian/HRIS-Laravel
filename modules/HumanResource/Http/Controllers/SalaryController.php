<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\EmployeeSalary as Model;
use Modules\HumanResource\Models\Employee as Employee;

use DB;
use Modules\HumanResource\Models\EmployeeSalary;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Model::select(['employee_salaries.id', 'employees.name as employee', 'payroll_components.id as payroll_component_id',
            'payroll_components.name as component', 'employee_salaries.amount', 'employee_salaries.created_at'])
            ->join('employees', 'employees.id', '=', 'employee_salaries.employee_id')
            ->join('payroll_components', 'payroll_components.id', '=', 'employee_salaries.payroll_component_id')
            ->where('employees.payroll_type_id', '=', 2);

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%');
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
            'basic_salary' => 'required',
            'wage' => 'required',
            'bonus' => 'required',
        ]);

        DB::beginTransaction();
        try {

            Model::create($request->all());

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
            'basic_salary' => 'required',
            'wage' => 'required',
            'bonus' => 'required',
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

    public function updateDataSalaries()
    {
        DB::beginTransaction();
        try {
            $migration = $this->getEmployee();
            // dd($migration);
            foreach ($migration as $migration) {
                $employeeid = $migration->id;

                $basicsalary = $this->getBasicSalary($employeeid);

                foreach ($basicsalary as $basicsalary) {
                    $data = [
                        'amount' => $migration->basic_salary,
                    ];

                    EmployeeSalary::whereId($basicsalary->id)->update($data);
                }

                $wage = $this->getWage($employeeid);

                foreach ($wage as $wage) {
                    $data = [
                        'amount' => $migration->wage,
                    ];

                    EmployeeSalary::whereId($wage->id)->update($data);
                }

                $functionalallowance = $this->getFunctionalAllowance($employeeid);

                foreach ($functionalallowance as $functionalallowance) {
                    $data = [
                        'amount' => $migration->functional_allowance,
                    ];

                    EmployeeSalary::whereId($functionalallowance->id)->update($data);
                }

                $meal = $this->getMeal($employeeid);

                foreach ($meal as $meal) {
                    $data = [
                        'amount' => $migration->meal,
                    ];

                    EmployeeSalary::whereId($meal->id)->update($data);
                }

                $aoc = $this->getAoc($employeeid);

                foreach ($aoc as $aoc) {
                    $data = [
                        'amount' => $migration->aoc,
                    ];

                    EmployeeSalary::whereId($aoc->id)->update($data);
                }
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function getEmployee()
    {
        $migration = DB::select("SELECT a.id, a.name,
        (SELECT amount FROM employee_salaries WHERE employee_id = a.id and payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'basic_salary')) AS basic_salary,
        (SELECT amount FROM employee_salaries WHERE employee_id = a.id and payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'wage')) AS wage,
        (SELECT amount FROM employee_salaries WHERE employee_id = a.id and payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'functional_allowance')) AS functional_allowance,
        (SELECT amount FROM employee_salaries WHERE employee_id = a.id and payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'meal')) AS meal,
        (SELECT amount FROM employee_salaries WHERE employee_id = a.id and payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'aoc')) AS aoc
        FROM employees a WHERE is_active = 1 AND payroll_type_id = 2");

        return $migration;
    }

    public function getBasicSalary($employeeid)
    {
        $basicsalary = DB::select("SELECT id FROM employee_salaries WHERE employee_id = :employeeid AND
        payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'basic_salary')",
        array('employeeid' => $employeeid));

        return $basicsalary;
    }

    public function getWage($employeeid)
    {
        $wage = DB::select("SELECT id FROM employee_salaries WHERE employee_id = :employeeid AND
        payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'wage')",
        array('employeeid' => $employeeid));

        return $wage;
    }

    public function getFunctionalAllowance($employeeid)
    {
        $functionalallowance = DB::select("SELECT id FROM employee_salaries WHERE employee_id = :employeeid AND
        payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'functional_allowance')",
        array('employeeid' => $employeeid));

        return $functionalallowance;
    }

    public function getMeal($employeeid)
    {
        $meal = DB::select("SELECT id FROM employee_salaries WHERE employee_id = :employeeid AND
        payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'meal')",
        array('employeeid' => $employeeid));

        return $meal;
    }

    public function getAoc($employeeid)
    {
        $aoc = DB::select("SELECT id FROM employee_salaries WHERE employee_id = :employeeid AND
        payroll_component_id = (SELECT id FROM payroll_components WHERE CODE = 'aoc')",
        array('employeeid' => $employeeid));

        return $aoc;
    }
}
