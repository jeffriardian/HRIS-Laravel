<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HumanResource\Imports\OvertimeImport;
use Modules\HumanResource\Models\AutoOvertimeRate;
use Modules\HumanResource\Models\Overtime as Model;
use Modules\HumanResource\Models\Overtime;
use Modules\HumanResource\Models\OvertimeApproval;
use Modules\HumanResource\Models\OvertimeHistory;
use Modules\HumanResource\Models\OvertimeRates;
use Modules\HumanResource\Models\OvertimeShiftEmployee;
use Modules\HumanResource\Models\TemporaryOvertime;
use Modules\HumanResource\Rules\ExcelRule;
use Modules\HumanResource\Services\CalculateOvertimeService;
use Modules\HumanResource\Services\CalculateOvertimeServiceV2;
use Modules\HumanResource\Services\CalculateOvertimeServiceV3;
use Modules\HumanResource\Services\CalculateOvertimeServiceV4;

class OvertimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $keyword = request()->keyword;
        $data = Model::with(['employee', 'approval', 'shift'])->where(function ($query) use ($keyword) {
            $query->whereHas('employee', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')->orWhere('nik', 'LIKE', '%' . $keyword . '%');
            });
        })->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

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
        $rules = [
            'employee_id' => 'required',
            'approved_by' => 'required',
            'request_date' => 'required',
            'description' => 'required',
            'attachment' => 'required|mimes:png,jpg,jpeg',
            'shift_id' => 'required',
            'schedule_in' => 'required',
            'schedule_out' => 'required'
        ];

        if ($request->is_holiday == 1) {
            if (!is_null($request->end_time_holiday) || !empty($request->end_time_holiday)) {
                $rules += ['start_time_holiday' => 'required'];
            }

            if (!is_null($request->start_time_holiday) || !empty($request->start_time_holiday)) {
                $rules += ['end_time_holiday' => 'required'];
            }
        }


        if (empty($request->overtime_before_duration) && empty($request->overtime_after_duration)) {
            $rules += ['duration' => 'required'];
        }

        $this->validate($request, $rules, ['duration.required' => 'One of the overtime durations is required'], ['shift_id' => 'Shift']);

        DB::beginTransaction();
        try {
            $file = $request->file('attachment');
            $fileName = time() . '.' . $file->getClientOriginalExtension();

            $file->storeAs('/public/human-resources/overtime', $fileName);

            $data = $request->all();
            $data['attachment'] =  $fileName;

            if ($request->is_holiday == 1) {
                if ($request->start_time_holiday != "null") {
                    $data['start_time_holiday'] = "$request->request_date $request->start_time_holiday:00";
                    if (strtotime($request->end_time_holiday) <= strtotime($request->start_time_holiday) + 300) {
                        $end = date('Y-m-d', strtotime($request->request_date . " +1 days"));
                        $data['end_time_holiday'] = "$end $request->end_time_holiday:00";
                    } else {
                        $data['end_time_holiday'] = "$request->request_date $request->end_time_holiday:00";
                    }
                }

                if (is_null($request->start_time_holiday)) {
                    $data['start_time_holiday'] = null;
                    $data['end_time_holiday'] = null;
                }
            }

            $overtime = Model::updateOrCreate([
                'employee_id' => $request->employee_id,
                'request_date' => $request->request_date
            ], $data);

            OvertimeShiftEmployee::create([
                'overtime_id' => $overtime->id,
                'shift_id' => $request->shift_id,
                'schedule_in' => $request->schedule_in,
                'schedule_out' => $request->schedule_out,
            ]);

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $e->getMessage()]);
        }
    }

    public function storeToTemporary(Request $request)
    {
        $rules = [
            'excel' => 'required',
            'approved_by' => 'required',
            'attachment' => 'required|mimes:jpg,jpeg,png'
        ];

        if ($request->excel != null) {
            $rules['excel'] = new ExcelRule($request->file('excel'));
        }

        $this->validate($request, $rules);

        $file = $request->file('attachment');
        $fileName = time() . '.' . $file->getClientOriginalExtension();

        $path = $request->file('excel');
        Excel::import(new OvertimeImport($request->approved_by, $fileName), $path);

        $file->storeAs('/public/human-resources/overtime', $fileName);
        return response()->json(['status' => 'success', 'data' => TemporaryOvertime::with('employee')->get()], Response::HTTP_CREATED);
    }

    public function migrationFromTemporary()
    {
        $temp = TemporaryOvertime::get()->each(function ($oldRecord) {
            return  $oldRecord->replicate();
        });

        DB::beginTransaction();
        try {
            foreach ($temp as $key) {
                $overtime = Overtime::updateOrCreate([
                    'employee_id' => $key->employee_id,
                    'request_date' => $key->request_date,
                ], [
                    'employee_id' => $key->employee_id,
                    'approved_by' => $key->approved_by,
                    'request_date' => $key->request_date,
                    'overtime_before_duration' =>  $key->overtime_before_duration,
                    'overtime_after_duration' => $key->overtime_after_duration,
                    'total_time' => $key->total_time,
                    'attachment' => $key->attachment,
                    'description' =>  $key->description,
                    'without_reducing_rest_hours' => $key->without_reducing_rest_hours,
                    'is_holiday' => $key->is_holiday,
                    'is_saturday' => $key->is_saturday,
                    'start_time_holiday' => $key->start_time_holiday,
                    'end_time_holiday' => $key->end_time_holiday
                ]);

                OvertimeShiftEmployee::updateOrCreate([
                    'overtime_id' => $overtime->id,
                ], [
                    'overtime_id' => $overtime->id,
                    'shift_id' => $key->shift_id,
                    'schedule_in' => $key->schedule_in,
                    'schedule_out' => $key->schedule_out,
                ]);
            }

            TemporaryOvertime::truncate();
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $th->getMessage()]);
        }
    }

    public function cancelStoreImport()
    {
        TemporaryOvertime::truncate();
        return response()->json(['status' => 'success'], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Model::with([
            'shift.shift',
            'approval',
            'employee.company',
            'employee.position',
            'employee.department',
            'histories',
            'overtimeRates'
        ])->findOrFail($id);
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
        $rules = [
            'employee_id' => 'required',
            'approved_by' => 'required',
            'request_date' => 'required',
            'description' => 'required',
            'attachment' => 'nullable|mimes:png,jpg,jpeg',
            'shift_id' => 'required',
            'schedule_in' => 'required',
            'schedule_out' => 'required'
        ];

        if ($request->is_holiday == 1) {
            if (!is_null($request->end_time_holiday) || !empty($request->end_time_holiday)) {
                $rules += ['start_time_holiday' => 'required'];
            }

            if (!is_null($request->start_time_holiday) || !empty($request->start_time_holiday)) {
                $rules += ['end_time_holiday' => 'required'];
            }
        }

        if ((empty($request->overtime_before_duration) || $request->overtime_before_duration == "null") && (empty($request->overtime_after_duration) || $request->overtime_after_duration == "null")) {
            $rules += ['duration' => 'required'];
        }

        $this->validate($request, $rules, ['duration.required' => 'One of the overtime durations is required'], ['shift_id' => 'Shift']);

        DB::beginTransaction();
        try {
            $overtime = Model::findOrFail($id);

            $fileName = $overtime->attachment;

            if ($request->hasFile('attachment')) {
                $file = $request->file('attachment');
                $fileName = $request->name . '-' . time() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('/public/human-resources/overtime', $fileName);
            }

            $data = $request->except(['_method']);
            $data['overtime_before_duration'] = $request->overtime_before_duration == "null" ? null : $request->overtime_before_duration;
            $data['overtime_after_duration'] = $request->overtime_after_duration == "null" ? null : $request->overtime_after_duration;
            $data['attachment'] =  $fileName;
            $data['total_time'] = (float) $request->total_time;

            if ($request->is_holiday == 1) {
                if ($request->start_time_holiday != "null") {
                    $startTime = explode(":",  $request->start_time_holiday);
                    $endTime = explode(":",  $request->end_time_holiday);

                    if (count($startTime) > 3) {
                        $startTime = "$startTime[0]:$startTime[1]:$startTime[2]";
                    } else {
                        $startTime = "$request->start_time_holiday";
                    }

                    if (count($endTime) > 3) {
                        $endTime = "$endTime[0]:$endTime[1]:$endTime[2]";
                    } else {
                        $endTime = "$request->end_time_holiday";
                    }

                    $data['start_time_holiday'] = "$request->request_date $startTime";
                    if (strtotime($request->end_time_holiday) <= strtotime($request->start_time_holiday) + 300) {
                        $end = date('Y-m-d', strtotime($request->request_date . " +1 days"));
                        $data['end_time_holiday'] = "$end $endTime";
                    } else {
                        $data['end_time_holiday'] = "$request->request_date $endTime";
                    }
                } else {
                    $data['start_time_holiday'] = null;
                    $data['end_time_holiday'] = null;
                }
            } else {
                $data['start_time_holiday'] = null;
                $data['end_time_holiday'] = null;
            }
            $overtime->update($data);

            OvertimeShiftEmployee::where('overtime_id', $id)->first()->update([
                'shift_id' => $request->shift_id,
                'schedule_in' => $request->schedule_in,
                'schedule_out' => $request->schedule_out,
            ]);

            $approval = OvertimeApproval::where('overtime_id', $id)->first();

            if (
                $overtime->wasChanged('employee_id') ||
                $overtime->wasChanged('approved_by') ||
                $overtime->wasChanged('request_date') ||
                $overtime->wasChanged('overtime_before_duration') ||
                $overtime->wasChanged('overtime_after_duration') ||
                $overtime->wasChanged('total_time') ||
                $overtime->wasChanged('attachment') ||
                $overtime->wasChanged('description') ||
                $overtime->wasChanged('without_reducing_rest_hours') ||
                $overtime->wasChanged('is_holiday') ||
                $overtime->wasChanged('is_saturday') ||
                $overtime->wasChanged('start_time_holiday') ||
                $overtime->wasChanged('end_time_holiday')
            ) {
                if ($approval != null) $approval->delete();
                if (
                    $overtime->wasChanged('total_time') ||
                    $overtime->wasChanged('without_reducing_rest_hours') ||
                    $overtime->wasChanged('is_holiday') ||
                    $overtime->wasChanged('is_saturday') ||
                    $overtime->wasChanged('start_time_holiday') ||
                    $overtime->wasChanged('end_time_holiday')
                ) {
                    OvertimeRates::where('overtime_id', $id)->delete();
                }

                OvertimeHistory::create([
                    'overtime_id' => $id,
                    'overtime_before_duration' => $overtime->overtime_before_duration,
                    'overtime_after_duration' => $overtime->overtime_after_duration,
                    'total_time' => $overtime->total_time,
                    'approved_status' => is_null($approval) ? null : $approval->approved_status,
                    'reject_description' => is_null($approval) ? null : $approval->reject_description,
                ]);
            }

            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $th->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Model::findOrFail($id)->delete();

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

    public function storeApproval(Request $request)
    {
        $rules = ['approved_status' => 'required'];

        if ($request->approved_status == 0 && !is_null($request->approved_status)) {
            $rules += ['reject_description' => 'required'];
        }

        $this->validate($request, $rules, [], ['reject_description' => 'description']);

        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['approved_by'] = Auth::id();
            OvertimeApproval::updateOrCreate([
                'overtime_id' => $request->overtime_id
            ], $data);

            $overtime = Overtime::whereId($request->overtime_id)->first();
            $history = $request->except(['approved_by']);
            $history['overtime_id'] = $request->overtime_id;
            $history['overtime_before_duration'] = $overtime->overtime_before_duration;
            $history['overtime_after_duration'] = $overtime->overtime_after_duration;
            $history['total_time'] = $overtime->total_time;

            OvertimeHistory::create($history);
            if ($request->approved_status == 1) {
                // (new CalculateOvertimeServiceV2())->calculateHourlyOvertimeAndRatesBySPL($overtime);
                // (new CalculateOvertimeServiceV3())->autoCalculateHourlyOvertime();
                (new CalculateOvertimeServiceV4())->calculateHourlyOvertimeAndRatesBySPL($overtime);
                // (new CalculateOvertimeServiceV4())->autoCalculateHourlyOvertime();
            } else {
                $overtimeRates = OvertimeRates::where('overtime_id', $overtime->id)->first();
                if (!is_null($overtimeRates)) $overtimeRates->delete();
            }
            DB::commit();
            return response()->json(['status' => 'success'], Response::HTTP_OK);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['status' => 'error', 'data' => $th->getMessage(), 'line' => $th->getLine(), 'file' => $th->getFile()]);
        }
    }

    public function getDataAutoOvertime()
    {
        $keyword = request()->keyword;
        $data = AutoOvertimeRate::with('employee')->where(function ($query) use ($keyword) {
            $query->whereHas('employee', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%')->orWhere('nik', 'LIKE', '%' . $keyword . '%');
            });
        })->orWhere('date', $keyword)->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        return new DataCollection($data->paginate(request()->per_page));
    }
}
