<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\HumanResource\Models\OfficeHour as Model;

class OfficeHourController extends Controller
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
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = Model::with('details')->where(function ($query) {
        //     $query->whereHas('details', function ($q) {
        //         $q->where('weekday_in', 'LIKE', '%' . request()->keyword . '%')
        //             ->orWhere('weekday_out', 'LIKE', '%' . request()->keyword . '%')
        //             ->orWhere('weekend_in', 'LIKE', '%' . request()->keyword . '%')
        //             ->orWhere('weekend_out', 'LIKE', '%' . request()->keyword . '%');
        //     })->orWhere('name', 'LIKE', '%' . request()->keyword . '%');
        // })->latest()->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        // return new DataCollection($data->paginate(request()->per_page));
        $data = Model::where('weekday_in', 'LIKE', '%' . request()->keyword . '%')
            ->orWhere('weekday_out', 'LIKE', '%' . request()->keyword . '%')
            ->orWhere('weekend_in', 'LIKE', '%' . request()->keyword . '%')
            ->orWhere('weekend_out', 'LIKE', '%' . request()->keyword . '%')
            ->orWhere('name', 'LIKE', '%' . request()->keyword . '%')
            ->latest()->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
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
        // $this->validate($request, [
        //     'name' => 'required',
        //     'details.*.weekday_in' => 'required',
        //     'details.*.weekday_out' => 'required',
        //     'details.*.weekend_in' => 'required',
        //     'details.*.weekend_out' => 'required',
        // ], [],  $this->changeAttributeValidationMessage);

        $this->validate($request, [
            'name' => 'required|unique:office_hours',
            'details.weekday_in' => 'required',
            'details.weekday_out' => 'required',
            'details.weekend_in' => 'required',
            'details.weekend_out' => 'required',
        ], [],  $this->changeAttributeValidationMessage);

        DB::beginTransaction();
        try {
            // $officeHour = Model::create(['name' => $request->name]);

            // $details = [];

            // for ($i = 0; $i < count($request->details); $i++) {
            //     $result = [];
            //     foreach ($request->details[$i] as $key => $val) {
            //         $result += [$key => Carbon::parse($val)->format('H:i:s')];
            //     }
            //     array_push($details, $result);
            // }

            // $officeHour->details()->createMany($details);

            // $officeHour->details()->create($request->details);
            Model::create([
                'name' => $request->name,
                'weekday_in' => $request->details['weekday_in'],
                'weekday_out' => $request->details['weekday_out'],
                'weekend_in' => $request->details['weekend_in'],
                'weekend_out' => $request->details['weekend_out']
            ]);

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $th->getMessage()]);
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
        // $this->validate($request, [
        //     'name' => 'required',
        //     'details.*.weekday_in' => 'required',
        //     'details.*.weekday_out' => 'required',
        //     'details.*.weekend_in' => 'required',
        //     'details.*.weekend_out' => 'required',
        // ], [], $this->changeAttributeValidationMessage);

        $this->validate($request, [
            'name' => 'required |unique:office_hours,name,' . $id,
            'details.weekday_in' => 'required',
            'details.weekday_out' => 'required',
            'details.weekend_in' => 'required',
            'details.weekend_out' => 'required',
        ], [], $this->changeAttributeValidationMessage);

        DB::beginTransaction();
        try {
            $officehour = Model::findOrFail($id);
            $officehour->update([
                'name' => $request->name,
                'weekday_in' => $request->details['weekday_in'],
                'weekday_out' => $request->details['weekday_out'],
                'weekend_in' => $request->details['weekend_in'],
                'weekend_out' => $request->details['weekend_out']
            ]);
            // $officehour->update($request->except(['details']));

            // proccesRelationWithRequest(
            //     $officehour->details(),
            //     $request->details
            // );

            // proccesRelationWithRequest(
            //     $officehour->details(),
            //     [$request->details]
            // );

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    /**
     * Export excel function.
     *
     * @return \Maatwebsite\Excel\Facades\Excel
     */
    public function exportExcel()
    {
        //    
    }

    /**
     * Export excel function.
     *
     * @return \Barryvdh\DomPDF\Facade
     */
    public function exportPdf()
    {
        // 
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
}
