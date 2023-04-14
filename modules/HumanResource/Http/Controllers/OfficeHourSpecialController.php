<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Models\OfficeHourSpecial as Model;

class OfficeHourSpecialController extends Controller
{
    // private $changeAttributeValidationMessage = [
    //     "details.*.weekday_in" => 'weekday in',
    //     "details.*.weekday_out" => 'weekday out',
    //     "details.*.weekend_in" => 'weekend in',
    //     "details.*.weekend_out" => 'weekend out',
    // ];

    private $changeAttributeValidationMessage = [
        "details.weekday_in" => 'weekday in',
        "details.weekday_out" => 'weekday out',
        "details.weekend_in" => 'weekend in',
        "details.weekend_out" => 'weekend out',
    ];
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = Model::with('details')->where(function ($query) {
            $query->whereHas('details', function ($q) {
                $q->where('weekday_in', 'LIKE', '%' . request()->keyword . '%')
                    ->orWhere('weekday_out', 'LIKE', '%' . request()->keyword . '%')
                    ->orWhere('weekend_in', 'LIKE', '%' . request()->keyword . '%')
                    ->orWhere('weekend_out', 'LIKE', '%' . request()->keyword . '%');
            })->orWhere('name', 'LIKE', '%' . request()->keyword . '%');
        })->latest()->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('humanresource::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'details.*.weekday_in' => 'required',
        //     'details.*.weekday_out' => 'required',
        //     'details.*.weekend_in' => 'required',
        //     'details.*.weekend_out' => 'required',
        // ], [],  $this->changeAttributeValidationMessage);

        $request->validate([
            'name' => 'required',
            'details.weekday_in' => 'required',
            'details.weekday_out' => 'required',
            'details.weekend_in' => 'required',
            'details.weekend_out' => 'required',
        ], [],  $this->changeAttributeValidationMessage);

        DB::beginTransaction();
        try {
            $officeHour = Model::create(['name' => $request->name]);

            // $details = [];

            // for ($i = 0; $i < count($request->details); $i++) {
            //     $result = [];
            //     foreach ($request->details[$i] as $key => $val) {
            //         $result += [$key => Carbon::parse($val)->format('H:i:s')];
            //     }
            //     array_push($details, $result);
            // }

            // $officeHour->details()->createMany($details);

            $officeHour->details()->create($request->details);

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $th->getMessage()]);
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $data = Model::with('details')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('humanresource::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'details.*.weekday_in' => 'required',
        //     'details.*.weekday_out' => 'required',
        //     'details.*.weekend_in' => 'required',
        //     'details.*.weekend_out' => 'required',
        // ], [], $this->changeAttributeValidationMessage);

        $request->validate([
            'name' => 'required',
            'details.weekday_in' => 'required',
            'details.weekday_out' => 'required',
            'details.weekend_in' => 'required',
            'details.weekend_out' => 'required',
        ], [], $this->changeAttributeValidationMessage);

        DB::beginTransaction();
        try {
            $officehour = Model::findOrFail($id);
            $officehour->update($request->except(['details']));

            // proccesRelationWithRequest(
            //     $officehour->details(),
            //     $request->details
            // );

            proccesRelationWithRequest(
                $officehour->details(),
                [$request->details]
            );

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $data = Model::findOrFail($id);
        $data->delete();
        return response()->json(['status' => 'success'], Response::HTTP_OK);
    }
}
