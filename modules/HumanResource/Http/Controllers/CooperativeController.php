<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\CooperativeMember as Model;

use DB;

class CooperativeController extends Controller
{
    public function index()
    {
        $data = Model::select(['cooperative_members.*', 'companies.name as company',
            'departments.name as department', 'cooperative_member_types.name as member_type'])
            ->join('companies', 'companies.id', '=', 'cooperative_members.company_id')
            ->join('departments', 'departments.id', '=', 'cooperative_members.department_id')
            ->join('cooperative_member_types', 'cooperative_member_types.id', '=', 'cooperative_members.cooperative_member_type_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('departments.name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    // public function loadAll()
    // {
    //     $data = DB::select('select * from employees where id in (select employee_id from cooperatives)');
    //     return new DataCollection($data);
    // }

    public function store(Request $request)
    {
        $this->validate($request, [
            'cooperative_member_type_id' => 'required',
            'join_date' => 'required',
            'company_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $companyid = $request->input('company_id');

            $member = $this->getMember($companyid);

            if (!empty($member)) {
                foreach($member as $member){
                    if (($request->input('company_id')) == 1 or ($request->input('company_id')) == 2) {
                        $employeeid = $request->input('employee_id');

                        $employee = $this->getEmployee($employeeid);

                        if (!empty($employee)) {
                            foreach($employee as $employee){

                                $data = [
                                    'company_id' => $request->input('company_id'),
                                    'department_id' => $employee->department_id,
                                    'employee_id' => $request->input('employee_id'),
                                    'cooperative_member_type_id' => $request->input('cooperative_member_type_id'),
                                    'id_member' => $member->no,
                                    'name' => $employee->name,
                                    'join_date' => date('Y-m-d', strtotime($request->input('join_date'))),
                                    'saving_balance' => '0',
                                    'loan_balance' => '0',
                                    'installment_count' => '0',
                                    'installment_payment' => '0',
                                    'approval_status_id' => '1',
                                ];

                                Model::create($data);

                            }
                        }

                    }else if (($request->input('company_id')) == 5 or ($request->input('company_id')) == 6) {

                        $data = [
                            'company_id' => $request->input('company_id'),
                            'department_id' => '999',
                            'employee_id' => '999',
                            'cooperative_member_type_id' => $request->input('cooperative_member_type_id'),
                            'id_member' => $member->no,
                            'name' => $request->input('txtemployee_id'),
                            'join_date' => date('Y-m-d', strtotime($request->input('join_date'))),
                            'saving_balance' => '0',
                            'loan_balance' => '0',
                            'installment_count' => '0',
                            'installment_payment' => '0',
                            'approval_status_id' => '1',
                        ];

                        Model::create($data);

                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $data = Model::with('employee')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'employee_id' => 'required',
            'join_date' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = Model::findOrFail($id);
            $data->update($request->all());

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_OK);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
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

    // public function GetMember()
    // {
    //     $member = DB::select('select (MAX(id)+1) as id, concat("KPR", "/", "SMM", "/",YEAR(NOW()),"/",LPAD(((select coalesce(MAX(id),0)
    //     from cooperative_members where YEAR(created_at) = YEAR(NOW()))+1),"5","0")) AS no
    //     from cooperative_members');

    //     return $member;
    // }

    public function GetMember($companyid)
    {
        $member = DB::select('select (MAX(id)+1) as id, concat(LPAD(:companyid,"2","0"),".",LPAD(((select coalesce(MAX(id),0)
        from cooperative_members where YEAR(created_at) = YEAR(NOW()))+1),"4","0")) AS no
        from cooperative_members',
        array('companyid'=>$companyid));

        return $member;
    }

    public function getEmployee($employeeid)
    {
        $member = DB::select("select id, department_id, name from employees where id = :employeeid",
        array('employeeid' => $employeeid));

        return $member;
    }
}
