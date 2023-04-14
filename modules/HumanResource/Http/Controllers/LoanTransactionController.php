<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\CooperativeLoanTransaction as Model;
use Modules\HumanResource\Models\CooperativeMember as Cooperative;

use DB;

class LoanTransactionController extends Controller
{
    public function index()
    {
        $data = Model::select(['cooperative_loan_transactions.*', 'cooperative_members.name as employee', 'cooperative_descriptions.name as cooperative'])
                ->join('cooperative_members', 'cooperative_members.id', '=', 'cooperative_loan_transactions.cooperative_member_id')
                ->join('cooperative_descriptions', 'cooperative_descriptions.id', '=', 'cooperative_loan_transactions.cooperative_description_id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('cooperative_members.name', 'LIKE', '%' . request()->keyword . '%');
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
        $this->validate($request, [
            'cooperative_member_id' => 'required',
            'amount' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'cooperative_member_id' => $request->input('cooperative_member_id'),
                'cooperative_description_id' => '6',
                'amount' => $request->input('amount'),
                'is_active' => '1',
            ];

            Model::create($data);

            $no = $request->input('cooperative_member_id');
            $loan = $this->getLoan($no);

            foreach($loan as $loan){
                $id = $loan->id;

                $data_balance = [
                    'loan_balance' => $loan->loan_balance,
                    'installment_count' => $loan->installment_count,
                ];

                Cooperative::whereId($id)->update($data_balance);
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
        $data = Model::with('cooperative')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'cooperative_member_id' => 'required',
            'amount' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = Model::findOrFail($id);
            $data->update($request->all());

            $no = $request->input('cooperative_member_id');
            $loan = $this->getLoan($no);

            foreach($loan as $loan){
                $id = $loan->id;

                $data_balance = [
                    'loan_balance' => $loan->loan_balance,
                    'installment_count' => $loan->installment_count,
                ];

                Cooperative::whereId($id)->update($data_balance);
            }

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

    // public function getLoan($no)
    // {
    //     $loanbalance = DB::select('select distinct(a.employee_id) as employee_id, e.id, ((select coalesce(sum(b.amount),0) as amount
    //     from loan_transactions b where b.employee_id = a.employee_id and b.cooperative_description_id in (select c.id from
    //     cooperative_descriptions c where c.cooperative_category_id = (select d.id from cooperative_categories d where d.name = :credit1)
    //     and c.cooperative_type_id = (select d.id from cooperative_types d where d.name = :loan1)))-(select coalesce(sum(b.amount),0)
    //     as amount from loan_transactions b where b.employee_id = a.employee_id and b.cooperative_description_id in (select c.id from
    //     cooperative_descriptions c where c.cooperative_category_id = (select d.id from cooperative_categories d where d.name = :debet1)
    //     and c.cooperative_type_id = (select d.id from cooperative_types d where d.name = :loan2)))) as selisih from
    //     loan_transactions a inner join cooperatives e on a.employee_id=e.employee_id where a.employee_id = :no',
    //     array('no'=>$no, 'loan1'=>'loan', 'loan2'=>'loan', 'credit1'=>'credit', 'debet1'=>'debet'));

    //     return $loanbalance;
    // }

    public function getLoan($no)
    {
        $loan = DB::select('SELECT a.id, a.installment_payment,

        ((SELECT SUM(amount) FROM cooperative_loan_transactions WHERE cooperative_member_id=a.id AND cooperative_description_id = 5)-
        (SELECT SUM(amount) FROM cooperative_loan_transactions WHERE cooperative_member_id=a.id AND cooperative_description_id = 6)) AS loan_balance,

        (((SELECT SUM(amount) FROM cooperative_loan_transactions WHERE cooperative_member_id=a.id AND cooperative_description_id = 5)-
        (SELECT SUM(amount) FROM cooperative_loan_transactions WHERE cooperative_member_id=a.id AND cooperative_description_id = 6)) div
        a.installment_payment) AS installment_count

        FROM cooperative_members a
        where a.id = :no',
        array('no' => $no));

        return $loan;
    }
}
