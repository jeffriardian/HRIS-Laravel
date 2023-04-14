<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use Modules\HumanResource\Models\PublicHoliday as Model;

use Excel;
use PDF;
use DB;
use Modules\HumanResource\Models\Year;

class PublicHolidayController extends Controller
{
    public function index()
    {
        $data = Model::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        if (request()->keyword != '') {
            $data = $data->where('name', 'LIKE', '%' . request()->keyword . '%');
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
            'description' => 'required|string|max:255',
            'holiday_date' => 'required',
        ]);

        $data = [
            'holiday_date' => date('Y-m-d', strtotime($request->input('holiday_date'))),
            'description' => $request->input('description'),
            'is_active' => '1',
        ];

        Model::create($data);

        return response()->json(['status' => 'success'], Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $data = Model::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'description' => 'required|string|max:255',
            'holiday_date' => 'required',
        ]);

        $data = [
            'holiday_date' => date('Y-m-d', strtotime($request->input('holiday_date'))),
            'description' => $request->input('description'),
        ];

        Model::whereId($id)->update($data);

        return response()->json(['status' => 'success'], Response::HTTP_OK);
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

    public function apiHoliday(Request $request)
    {
        DB::beginTransaction();
        try {
            $tahun = now()->format('Y');

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://cdn.jsdelivr.net/gh/niyoko/libur-nasional/data/$tahun.json",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "x-rapidapi-host: public-holiday.p.rapidapi.com",
                    "x-rapidapi-key: b6217662dfmsh79b50c8fb3ca588p1e291cjsn162ef3b3f0e3"
                ),
            ));

            $response = curl_exec($curl);
            $holiday = json_decode($response);

            if (!empty($holiday)){
                foreach ($holiday as $holiday) {
                    $date = $holiday->date;
                    $description = $holiday->description;

                    $check = $this->checkHoliday( $date, $description);

                    if (!empty($check))
                    {
                        foreach($check as $check){
                            $publicholidayid = $check->id;

                            $data = [
                                'holiday_date' => $holiday->date,
                                'description'  => $holiday->description,
                            ];

                            Model::whereId($publicholidayid)->update($data);
                        }
                    }else{
                        $data = [
                            'holiday_date' => $holiday->date,
                            'description'  => $holiday->description,
                            'is_active'    => 1,
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

    public function checkHoliday( $date, $description)
    {
        $check = DB::select('SELECT * FROM public_holidays WHERE holiday_date = :holidaydate AND description = :name',
         array('holidaydate' => $date, 'name' => $description));

        return $check;
    }
}
