<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\BusinessTrip as Model;
use Modules\HumanResource\Models\BusinessTripParameter as Parameter;
use Modules\HumanResource\Models\BusinessTripTransportation as Transportation;
use Modules\HumanResource\Models\Leave as Leave;
use Modules\HumanResource\Models\Reimburse as Reimburse;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\HumanResource\Models\BusinessTripImage;
use Modules\HumanResource\Models\BusinessTripParameter;
use Modules\HumanResource\Models\BusinessTripTransportation;

use function GuzzleHttp\json_decode;

class BusinessTripController extends Controller
{
    public function index()
    {
        $data = Model::select([
            'business_trips.id', 'business_trips.departure_date', 'business_trips.back_date', 'business_trips.destination', 'business_trips.created_at',
            'business_trips.purpose', 'employees.name as employee', 'business_trips.total_cost as receipt', 'approval_statuses.name as approval'
        ])
            ->join('employees', 'employees.id', '=', 'business_trips.employee_id')
            ->join('approval_statuses', 'approval_statuses.id', '=', 'business_trips.approval_status_id')
            ->where('business_trips.approval_status_id', '=', '1');

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
            'departure_date' => 'required',
            'back_date' => 'required',
            'destination' => 'required',
            'purpose' => 'required',
        ]);
        DB::beginTransaction();
        try {
            $number = $this->getNumber();

            if (!empty($number)) {
                foreach ($number as $number) {
                    $data = [
                        'business_trip_number' => $number->businessnumber,
                        'receipt_number' => $number->receiptnumber,
                        'employee_id' => $request->input('employee_id'),
                        'departure_date' => date('Y-m-d', strtotime($request->input('departure_date'))),
                        'back_date' => date('Y-m-d', strtotime($request->input('back_date'))),
                        'destination' => $request->input('destination'),
                        'purpose' => $request->input('purpose'),
                        'total_cost' => '0',
                        'approval_status_id' => '1',
                    ];

                    $businesstrip = Model::create($data);
                    $id = $businesstrip->id;
                    $data_parameter = [
                        'business_trip_id' => $businesstrip->id,
                        'receipt_parameter_id' => '0',
                        'cost' => '0'
                    ];

                    Parameter::create($data_parameter);
                    // foreach ($request->parameters as $parameter) {
                    //     if (!empty($parameter['receipt_parameter_id']) or ($parameter['receipt_parameter_id'] != NULL)) {
                    //         $data_parameter = [
                    //             'business_trip_id' => $businesstrip->id,
                    //             'receipt_parameter_id' => $parameter['receipt_parameter_id'],
                    //             'cost' => '0'
                    //         ];

                    //         Parameter::create($data_parameter);
                    //     }
                    // }

                    $data_transportation = [
                        'business_trip_id' => $businesstrip->id,
                        'transportation_id' => '0',
                        'cost' => '0'
                    ];

                    Transportation::create($data_transportation);

                    // foreach ($request->transportations as $transportation) {
                    //     if (!empty($transportation['transportation_id']) or ($transportation['transportation_id'] != NULL)) {
                    //         $data_transportation = [
                    //             'business_trip_id' => $businesstrip->id,
                    //             'transportation_id' => $transportation['transportation_id'],
                    //             'cost' => '0'
                    //         ];

                    //         Transportation::create($data_transportation);
                    //     }
                    // }

                    $costTransportation = $this->getCostTrasportation($id);

                    foreach ($costTransportation as $costTransportation) {
                        $transportationcode = $this->getTrasportation();

                        if (!empty($transportationcode)) {
                            foreach ($transportationcode as $transportationcode) {
                                $data_parameter = [
                                    'business_trip_id' => $businesstrip->id,
                                    'receipt_parameter_id' => $transportationcode->id,
                                    'cost' => $costTransportation->totalcost,
                                ];

                                Parameter::create($data_parameter);
                            }
                        }
                    }

                    $costReceipt = $this->getCostReceipt($id);

                    foreach ($costReceipt as $costReceipt) {
                        $update_cost = [
                            'total_cost'        => $costReceipt->totalcost,
                        ];

                        Model::whereId($id)->update($update_cost);
                    }

                    if ($request->reimburse_type != "") {
                        $reimburse = $this->GetReimburseCategory();

                        if (!empty($reimburse)) {
                            foreach ($reimburse as $reimburse) {
                                $business = $this->GetBusinessTrip($id);

                                if (!empty($business)) {
                                    foreach ($business as $business) {
                                        $data_reimburse = [
                                            'reimburse_category_id' => $reimburse->id,
                                            'employee_id' => $business->employee_id,
                                            'reimburse_number' => $business->business_trip_number,
                                            'description' => $business->purpose,
                                            'reimburse_type' => $request->input('reimburse_type'),
                                            'total_cost' => $business->total_cost,
                                        ];

                                        Reimburse::create($data_reimburse);
                                    }
                                }
                            }
                        }
                    }


                    $departure = date('Y-m-d', strtotime($request->input('departure_date')));
                    $back = date('Y-m-d', strtotime($request->input('back_date')));

                    $leavecategory = $this->getLeaveCategory($departure, $back);

                    if (!empty($leavecategory)) {
                        foreach ($leavecategory as $leavecategory) {
                            $leave = [
                                'leave_category_id'  => $leavecategory->id,
                                'employee_id'   => $request->input('employee_id'),
                                'total' => $leavecategory->total,
                                'start_date'    => date('Y-m-d', strtotime($request->input('departure_date'))),
                                'end_date'  => date('Y-m-d', strtotime($request->input('back_date'))),
                            ];

                            Leave::create($leave);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $data = Model::with([
            'parameters' => function ($q) {
                $q->with('receiptParameter')->where('receipt_parameter_id', '!=', 1);
            },
            'transportations.transportation',
            'employee',
            'reimburse' => function ($q) {
                $q->where('reimburse_category_id', 1);
            },
            'images'
        ])->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        $this->validate($request, [
            'employee_id' => 'required',
            'departure_date' => 'required',
            'back_date' => 'required',
            'destination' => 'required',
            'purpose' => 'required',
            'business_trip_result' => 'required',
            'parameters.*.images.*' => 'sometimes|image|mimes:jpg,jpeg',
            'transportations.*.images.*' => 'sometimes|image|mimes:jpg,jpeg',
        ]);

        DB::beginTransaction();
        try {
            $databusiness = $this->getDataBusiness($id);

            if (!empty($databusiness)) {
                foreach ($databusiness as $databusiness) {
                    $departure = $databusiness->departure_date;
                    $back = $databusiness->back_date;
                    $employee = $databusiness->employee_id;
                    $newdeparture = date('Y-m-d', strtotime($request->input('departure_date')));
                    $newback = date('Y-m-d', strtotime($request->input('back_date')));

                    $leaveid = $this->getLeaveId($departure, $back, $employee, $newdeparture, $newback);

                    if (!empty($leaveid)) {
                        foreach ($leaveid as $leaveid) {
                            $leave = [
                                'total' => $leaveid->total,
                                'start_date' => $newdeparture,
                                'end_date' => $newback,
                            ];

                            Leave::whereId($leaveid->id)->update($leave);
                        }
                    }
                }
            }

            $data = [
                'employee_id' => $request->input('employee_id'),
                'departure_date' => date('Y-m-d', strtotime($request->input('departure_date'))),
                'back_date' => date('Y-m-d', strtotime($request->input('back_date'))),
                'destination' => $request->input('destination'),
                'purpose' => $request->input('purpose'),
                'business_trip_result' => $request->input('business_trip_result'),
            ];

            Model::findOrFail($id)->update($data);

            $businessTrip = Model::findOrFail($id);

            $dataParameters = [];
            foreach ($request->parameters as $key) {
                $data = (array) json_decode($key['data']);
                array_push($dataParameters, $data);
            }
            proccesRelationWithRequest(
                $businessTrip->parameters(),
                $dataParameters
            );

            $dataTransportations = [];
            foreach ($request->transportations as $key) {
                $data = (array) json_decode($key['data']);
                array_push($dataTransportations, $data);
            }
            proccesRelationWithRequest(
                $businessTrip->transportations(),
                $dataTransportations
            );

            $dataReimburse = [
                'reimburse_category_id' => 1,
                'employee_id' => $request->employee_id,
                'reimburse_number' => $businessTrip->business_trip_number,
                'description' => $request->description,
                'total_cost' => $request->total_cost,
                'reimburse_type' => $request->reimburse_type,
                'approval_status_id' => 1,
                'is_active' => 1,
            ];

            $checkReimburseIsExist = Reimburse::where('employee_id', $request->employee_id)->where('reimburse_number', $businessTrip->business_trip_number)->count();

            if ($checkReimburseIsExist == 0) {
                if ($request->reimburse_type != null) {
                    Reimburse::create($dataReimburse);
                }
            } else {
                if ($request->reimburse_type == null) {
                    Reimburse::where('employee_id', $request->employee_id)->where('reimburse_number', $businessTrip->business_trip_number)->delete();
                } else {
                    Reimburse::where('reimburse_number', $businessTrip->business_trip_number)->update($dataReimburse);
                }
            }


            $checkDeletedTotalCost = BusinessTripParameter::withTrashed()->where('business_trip_id', $id)->where('receipt_parameter_id', 1)->firstOrFail();
            if ($checkDeletedTotalCost->deleted_at != null) {
                BusinessTripParameter::withTrashed()->where('business_trip_id', $id)->where('receipt_parameter_id', 1)->restore();
            }
            $reqParameter = $request->parameters;
            foreach ($reqParameter as $key => $valueImage) {
                if (isset($valueImage['images'])) {
                    $index = $key;
                    $getBusinessParameters = BusinessTripParameter::where('business_trip_id', $id)->where('receipt_parameter_id', '!=', 1)->get();
                    $getBusinessTripImages = BusinessTripImage::where('business_trip_id', $id)
                        ->where('payment_slip_type', 0)
                        ->where('payment_slip_id', $getBusinessParameters[$index]->receipt_parameter_id)
                        ->get();
                    if (count($getBusinessTripImages) > 0) {
                        foreach ($getBusinessTripImages as $key) {
                            Storage::delete('public/images/business-trip/' . $key->image);
                        }
                        $getBusinessTripImages->each->delete();
                    }
                    foreach ($valueImage['images'] as $image) {
                        $file = (object) $image;
                        $nameImage = 'Allowance-' . Str::random(5) . '.' . $file->getClientOriginalExtension(); // membuat nama image
                        BusinessTripImage::create([
                            'business_trip_id' => $id,
                            'image' => $nameImage,
                            'payment_slip_type' => 0,
                            'payment_slip_id' =>  $getBusinessParameters[$index]->receipt_parameter_id
                        ]);
                        $file->storeAs('public/images/business-trip', $nameImage); // store image baru.
                    }
                }
            }

            $transportations = BusinessTripTransportation::where('business_trip_id', $id)->get();

            $totalCostTransportation = $transportations->sum('cost');
            BusinessTripParameter::where('business_trip_id', $id)->where('receipt_parameter_id', 1)->update(['cost' => $totalCostTransportation]);

            $reqTransportations = $request->transportations;
            foreach ($reqTransportations as $key => $valueImage) {
                if (isset($valueImage['images'])) {
                    $index = $key;
                    $getBusinessTransportation = BusinessTripTransportation::where('business_trip_id', $id)->get();
                    $getBusinessTripImages = BusinessTripImage::where('business_trip_id', $id)
                        ->where('payment_slip_type', 1)
                        ->where('payment_slip_id', $getBusinessTransportation[$index]->transportation_id)
                        ->get();
                    if (count($getBusinessTripImages) > 0) {
                        foreach ($getBusinessTripImages as $key) {
                            Storage::delete('public/images/business-trip/' . $key->image);
                        }
                        $getBusinessTripImages->each->delete();
                    }
                    foreach ($valueImage['images'] as $image) {
                        $file = (object) $image;
                        $nameImage = 'Transportation-' . Str::random(5) . '.' . $file->getClientOriginalExtension(); // membuat nama image
                        BusinessTripImage::create([
                            'business_trip_id' => $id,
                            'image' => $nameImage,
                            'payment_slip_type' => 1,
                            'payment_slip_id' => $getBusinessTransportation[$index]->transportation_id
                        ]);
                        $file->storeAs('public/images/business-trip', $nameImage); // store image baru
                    }
                }
            }

            $costReceipt = $this->getCostReceipt($id);

            foreach ($costReceipt as $costReceipt) {
                $update_cost = [
                    'total_cost' => $costReceipt->totalcost,
                ];

                Model::whereId($id)->update($update_cost);

                $reimburseid = $this->GetReimburseId($id);

                if (!empty($reimburseid)) {
                    foreach ($reimburseid as $reimburseid) {
                        $data_reimburse = [
                            'total_cost' => $costReceipt->totalcost,
                        ];

                        Reimburse::whereId($reimburseid->id)->update($data_reimburse);
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_OK);
        } catch (\Exception $e) {
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

    public function GetNumber()
    {
        $number = DB::select('select (MAX(id)+1) as id, concat("SPD", "/", "SMM", "/",YEAR(NOW()),"/",LPAD(((select coalesce(MAX(id),0)
        from business_trips where YEAR(created_at) = YEAR(NOW()))+1),"5","0")) AS businessnumber,
        concat("KUI", "/", "SMM", "/",YEAR(NOW()),"/",LPAD(((select coalesce(MAX(id),0)
        from business_trips where YEAR(created_at) = YEAR(NOW()))+1),"5","0")) AS receiptnumber
        from business_trips');

        return $number;
    }

    public function getTrasportation()
    {
        $transportationcode = DB::select(
            'select id from receipt_parameters where name = :name',
            array('name' => 'transportation')
        );

        return $transportationcode;
    }

    public function getCostTrasportation($id)
    {
        $costTransportation = DB::select(
            'select sum(cost) as totalcost from business_trip_transportations where business_trip_id = :id and ISNULL(deleted_at)',
            array('id' => $id)
        );

        return $costTransportation;
    }

    public function getCostReceipt($id)
    {
        $costReceipt = DB::select(
            'select sum(cost) as totalcost from business_trip_parameters where business_trip_id = :id and ISNULL(deleted_at)',
            array('id' => $id)
        );

        return $costReceipt;
    }

    public function getBusinessTripParameter($id)
    {
        $businesstripparameter = DB::select(
            'select id from business_trip_parameters where business_trip_id = :id and receipt_parameter_id =
        (select id from receipt_parameters where name = :name)  and ISNULL(deleted_at)',
            array('id' => $id, 'name' => 'transportation')
        );

        return $businesstripparameter;
    }

    public function getLeaveCategory($departure, $back)
    {
        $leavecategory = DB::select(
            'SELECT id, DATEDIFF(:back, :departure)+1 AS total FROM leave_categories WHERE NAME = :name',
            array('departure' => $departure, 'back' => $back, 'name' => 'Official Leave')
        );

        return $leavecategory;
    }

    public function GetBusinessTrip($id)
    {
        $business = DB::select(
            'select a.employee_id, a.business_trip_number, a.purpose, a.total_cost
        from business_trips a where a.id= :id',
            array('id' => $id)
        );

        return $business;
    }

    public function GetReimburseCategory()
    {
        $reimburse = DB::select(
            'select id from reimburse_categories where name= :name',
            array('name' => 'Business Trip')
        );

        return $reimburse;
    }

    public function GetReimburseId($id)
    {
        $reimburseid = DB::select(
            'SELECT id FROM reimburses WHERE reimburse_number = (SELECT business_trip_number FROM business_trips WHERE id = :id)',
            array('id' => $id)
        );

        return $reimburseid;
    }

    public function getDataBusiness($id)
    {
        $databusiness = DB::select(
            'SELECT employee_id, departure_date, back_date FROM business_trips WHERE id = :id',
            array('id' => $id)
        );

        return $databusiness;
    }

    public function getLeaveId($departure, $back, $employee, $newdeparture, $newback)
    {
        $leaveid = DB::select(
            'SELECT id, (DATEDIFF(:newback,:newdeparture)+1) AS total
        FROM leaves WHERE employee_id = :employee AND start_date = :departure AND end_date = :back',
            array('departure' => $departure, 'back' => $back, 'employee' => $employee, 'newdeparture' => $newdeparture, 'newback' => $newback)
        );

        return $leaveid;
    }
}
