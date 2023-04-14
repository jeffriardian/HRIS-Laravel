<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\CooperativeLoan as Model;
use Modules\HumanResource\Models\CooperativeMember as Cooperative;
use Modules\HumanResource\Models\CooperativeLoanTransaction as Loan;

use DB;

class LoanController extends Controller
{
    public function index()
    {
        $data = Model::select(['cooperative_loans.*', 'cooperative_members.name as employee', 'cooperative_members.loan_balance as balance',
                'cooperative_members.installment_payment as payment', 'cooperative_members.installment_count as installment_count'])
                ->join('cooperative_members', 'cooperative_members.id', '=', 'cooperative_loans.cooperative_member_id');

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
            'loan_date' => 'required',
            'amount' => 'required',
            'interest' => 'required',
            'total' => 'required',
            'installment_count' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = [
                'cooperative_member_id' => $request->input('cooperative_member_id'),
                'loan_date' => date('Y-m-d', strtotime($request->input('loan_date'))),
                'amount' => $request->input('amount'),
                'interest' => $request->input('interest'),
                'total' => $request->input('total'),
            ];

            Model::create($data);

            $no = $request->input('cooperative_member_id');
            $cooperative = $this->getCooperative($no);

            if (!empty($cooperative)) {
                foreach($cooperative as $cooperative){
                    $id = $cooperative->id;

                    $data_cooperative = [
                        'loan_balance' => $request->input('total'),
                        'installment_count' => $request->input('installment_count'),
                        'installment_payment' => $request->input('installment_payment'),
                    ];

                    Cooperative::whereId($id)->update($data_cooperative);
                }
            }

            $data_loan = [
                'cooperative_member_id' => $request->input('cooperative_member_id'),
                'cooperative_description_id' => '5',
                'amount' => $request->input('total'),
            ];

            Loan::create($data_loan);

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        // $data = Model::with('employee')->findOrFail($id);
        $data = Model::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'cooperative_member_id' => 'required',
            'loan_date' => 'required',
            'amount' => 'required',
            'interest' => 'required',
            'total' => 'required',
            'installment_count' => 'required',
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

    public function getCooperative($no)
    {
        $cooperative = DB::select('select id from cooperative_members where id = :no',
        array('no'=>$no));

        return $cooperative;
    }
}
