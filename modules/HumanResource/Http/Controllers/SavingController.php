<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\CooperativeSavingTransaction as Model;
use Modules\HumanResource\Models\CooperativeMember as Cooperative;

use DB;

class SavingController extends Controller
{
    public function index()
    {
        $data = Model::select(['cooperative_saving_transactions.*', 'cooperative_members.name as employee', 'cooperative_descriptions.name as cooperative'])
                ->join('cooperative_members', 'cooperative_members.id', '=', 'cooperative_saving_transactions.cooperative_member_id')
                ->join('cooperative_descriptions', 'cooperative_descriptions.id', '=', 'cooperative_saving_transactions.cooperative_description_id');

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
                'cooperative_description_id' => '3',
                'amount' => $request->input('amount'),
                'is_active' => '1',
            ];

            Model::create($data);

            $no = $request->input('cooperative_member_id');
            $savingbalance = $this->getSaving($no);

            foreach($savingbalance as $savingbalance){
                $id = $savingbalance->id;

                $data_balance = [
                    'saving_balance' => $savingbalance->saving_balance,
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
            $savingbalance = $this->getSaving($no);

            foreach($savingbalance as $savingbalance){
                $id = $savingbalance->id;

                $data_balance = [
                    'saving_balance' => $savingbalance->saving_balance,
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

    public function getSaving($no)
    {
        $savingbalance = DB::select("SELECT a.id,

        (SELECT coalesce(SUM(amount),0) FROM cooperative_saving_transactions
        WHERE (cooperative_member_id = 1) AND (cooperative_description_id IN
        (SELECT id FROM cooperative_descriptions WHERE cooperative_type_id =
        (SELECT id FROM cooperative_types WHERE NAME = 'Saving')))) AS saving_balance

        FROM cooperative_members a
        where a.id = :no",
        array('no'=>$no));

        return $savingbalance;
    }

    // public function getSaving($no)
    // {
    //     $savingbalance = DB::select('select distinct(a.employee_id) as employee_id, e.id, ((select coalesce(sum(b.amount),0) as amount
    //     from saving_transactions b where b.employee_id = a.employee_id and b.cooperative_description_id in (select c.id from
    //     cooperative_descriptions c where c.cooperative_category_id = (select d.id from cooperative_categories d where d.name = :credit1)
    //     and c.cooperative_type_id = (select d.id from cooperative_types d where d.name = :saving1)))-(select coalesce(sum(b.amount),0)
    //     as amount from saving_transactions b where b.employee_id = a.employee_id and b.cooperative_description_id in (select c.id from
    //     cooperative_descriptions c where c.cooperative_category_id = (select d.id from cooperative_categories d where d.name = :debet1)
    //     and c.cooperative_type_id = (select d.id from cooperative_types d where d.name = :saving2)))) as selisih from
    //     saving_transactions a inner join cooperatives e on a.employee_id=e.employee_id where a.employee_id = :no',
    //     array('no'=>$no, 'saving1'=>'saving', 'saving2'=>'saving', 'credit1'=>'credit', 'debet1'=>'debet'));

    //     return $savingbalance;
    // }
}
