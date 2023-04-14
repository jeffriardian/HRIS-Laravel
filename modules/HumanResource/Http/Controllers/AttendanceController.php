<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

use App\Http\Resources\DataCollection;
use Modules\HumanResource\Models\AttLog as AttLog;
use Modules\HumanResource\Models\TimeLog as TimeLog;
use Modules\HumanResource\Models\Attendance as Model;

use DB;
use Modules\HumanResource\Models\OvertimePayroll;

class AttendanceController extends Controller
{
    public function index()
    {
        $data = Model::select(['attendances.*', 'employees.name as employee', 'payroll_types.name as payrolltype'])
                ->join('employees', 'employees.id', '=', 'attendances.employee_id')
                ->join('payroll_types', 'employees.payroll_type_id', '=', 'payroll_types.id');

        $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

        if (request()->keyword != '') {
            $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    // public function index()
    // {
    //     $data = Model::select(['attendances.*', 'employees.name as employee'])
    //             ->join('employees', 'employees.id', '=', 'attendances.employee_id');

    //     $data->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));

    //     if (request()->keyword != '') {
    //         $data = $data->where('employees.name', 'LIKE', '%' . request()->keyword . '%');
    //     }
    //     return new DataCollection($data->paginate(request()->per_page));
    // }

    public function loadAll()
    {
        $data = Model::orderBy('id', 'ASC');
        return new DataCollection($data->get());
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'start_period' => 'required',
            'end_period' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $start = date('Y-m-d', strtotime($request->input('start_period')));
            $end = date('Y-m-d', strtotime($request->input('end_period')));

            //Tarik data dari SQL Server
            $attlog = $this->getAttLog($start, $end);

            if (!empty($attlog))
            {
                foreach($attlog as $attlog){
                    $pin = $attlog->pin;
                    $scan_date = $attlog->scan_date;

                    $check = $this->checkAttLog($pin, $scan_date);

                    if (empty($check))
                    {
                        $data = [
                            'sn' => $attlog->sn,
                            'scan_date' => $attlog->scan_date,
                            'pin' => $attlog->pin,
                            'verifymode' => $attlog->verifymode,
                            'inoutmode' => $attlog->inoutmode,
                            'reserved' => $attlog->reserved,
                            'work_code' => $attlog->work_code,
                            'att_id' => $attlog->att_id,
                            'is_active' => 1,
                        ];

                        AttLog::create($data);
                    }
                }
            }

            //Perhitungan Cuti
            while (strtotime($start) <= strtotime($end)){
                $date = $start;

                $checkdate = $this->checkDateAttendance($date);

                if (empty($checkdate)) {

                    $employee = $this->getEmployee();

                    if (!empty($employee)) {
                        foreach($employee as $employee){
                            $employeeid = $employee->id;
                            $pin = $employee->pin;

                            $checkweek = $this->checkWeekEnd($date);

                            foreach($checkweek as $checkweek){
                                $weekend = $checkweek->week;

                                if ($weekend == 0){
                                        $data = [
                                            'employee_id' => $employeeid,
                                            'in' => NULL,
                                            'out' => NULL,
                                            'is_active' => '1',
                                        ];

                                        TimeLog::create($data);

                                        $detail = [
                                            'employee_id' => $employeeid,
                                            'date_work' => $date,
                                            'time_in' => NULL,
                                            'in_time' => NULL,
                                            'time_out' => NULL,
                                            'out_time' => NULL,
                                            'status' => 'Libur',
                                            'total_time_work' => NULL,
                                            'is_active' => '1',
                                        ];

                                        Model::create($detail);

                                }else if ($weekend != 0){

                                    $holiday = $this->checkHoliday($date);

                                    if (!empty($holiday)) {
                                        $data = [
                                            'employee_id' => $employeeid,
                                            'in' => NULL,
                                            'out' => NULL,
                                            'is_active' => '1',
                                        ];

                                        TimeLog::create($data);

                                        $detail = [
                                            'employee_id' => $employeeid,
                                            'date_work' => $date,
                                            'time_in' => NULL,
                                            'in_time' => NULL,
                                            'time_out' => NULL,
                                            'out_time' => NULL,
                                            'status' => 'Public Holiday',
                                            'total_time_work' => NULL,
                                            'is_active' => '1',
                                        ];

                                        Model::create($detail);

                                    }else if (empty($holiday)) {
                                        $timelog = $this->getTimeLog($pin, $date);

                                        foreach($timelog as $timelog){
                                            if (!empty($timelog->in_time)) {
                                                $data = [
                                                    'employee_id' => $employeeid,
                                                    'in' => date('Y-m-d H:i:s', strtotime($timelog->in_time)),
                                                    'out' => date('Y-m-d H:i:s', strtotime($timelog->out_time)),
                                                    'is_active' => '1',
                                                ];

                                                TimeLog::create($data);

                                                $attendance = $this->getAttendance($employeeid, $date);

                                                if (!empty($attendance)) {
                                                    foreach($attendance as $attendance){
                                                        $detail = [
                                                            'employee_id' => $employeeid,
                                                            'date_work' => $date,
                                                            'time_in' => $attendance->time_in,
                                                            'in_time' => $attendance->in_time,
                                                            'time_out' => $attendance->time_out,
                                                            'out_time' => $attendance->out_time,
                                                            'status' => $attendance->attendance_status,
                                                            'total_time_work' => $attendance->total_time_work,
                                                            'is_active' => '1',
                                                        ];

                                                        Model::create($detail);
                                                    }
                                                }
                                            }else if (empty($timelog->in_time)) {
                                                $leave = $this->getLeave($employeeid, $date);

                                                if (!empty($leave)) {
                                                    foreach($leave as $leave){
                                                        $data = [
                                                            'employee_id' => $employeeid,
                                                            'in' => NULL,
                                                            'out' => NULL,
                                                            'is_active' => '1',
                                                        ];

                                                        TimeLog::create($data);

                                                        $detail = [
                                                            'employee_id' => $employeeid,
                                                            'date_work' => $date,
                                                            'time_in' => NULL,
                                                            'in_time' => NULL,
                                                            'time_out' => NULL,
                                                            'out_time' => NULL,
                                                            'status' => $leave->name,
                                                            'total_time_work' => NULL,
                                                            'is_active' => '1',
                                                        ];

                                                        Model::create($detail);
                                                    }
                                                }else if (empty($leave)) {
                                                    $data = [
                                                        'employee_id' => $employeeid,
                                                        'in' => NULL,
                                                        'out' => NULL,
                                                        'is_active' => '1',
                                                    ];

                                                    TimeLog::create($data);

                                                    $detail = [
                                                        'employee_id' => $employeeid,
                                                        'date_work' => $date,
                                                        'time_in' => NULL,
                                                        'in_time' => NULL,
                                                        'time_out' => NULL,
                                                        'out_time' => NULL,
                                                        'status' => 'Alpa',
                                                        'total_time_work' => NULL,
                                                        'is_active' => '1',
                                                    ];

                                                    Model::create($detail);
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                $start = date ("Y-m-d", strtotime("+1 day", strtotime($start)));
            }

            //Perhitungan Lembur
            $basicsaving = $this->getBasicSaving();

            if (!empty($basicsaving)) {
                foreach($basicsaving as $basicsaving){
                    $salary = $basicsaving->amount;

                    $overtime = $this->getOvertime($start, $end);

                    if (!empty($overtime)) {
                        foreach($overtime as $overtime){
                            $days = $overtime->days;
                            $total = $overtime->total_overtime;
                            $employeeid = $overtime->employee_id;

                            $salarytype = $this->getSalaryType($employeeid);

                            if (!empty($salarytype)) {
                                if ($days == 'Weekend'){

                                    if ($total <= 7){

                                        $overtime = [
                                            'employee_id' => $overtime->employee_id,
                                            'date_overtime' => date('Y-m-d', strtotime($overtime->date_start)),
                                            'schedule_start_overtime' => date('H:i:s', strtotime($overtime->start_time)),
                                            'start_overtime' => date('H:i:s', strtotime($overtime->in_time)),
                                            'schedule_end_overtime' => date('H:i:s', strtotime($overtime->end_time)),
                                            'end_overtime' => date('H:i:s', strtotime($overtime->out_time)),

                                            'overtime_1' => '0',
                                            'amount_overtime_1' => '0',
                                            'total_overtime_1' => '0',

                                            'overtime_2' => '0',
                                            'amount_overtime_2' => '0',
                                            'total_overtime_2' => '0',

                                            'overtime_weekend_2' => $total,
                                            'amount_overtime_weekend_2' => floor(($salary*2)/173),
                                            'total_overtime_weekend_2' => floor(($salary*2)/173)*($total),

                                            'overtime_weekend_3' => '0',
                                            'amount_overtime_weekend_3' => '0',
                                            'total_overtime_weekend_3' => '0',

                                            'total_overtime' => floor((($salary*2)/173)*($total)),
                                            'is_active' => '1',
                                        ];

                                        OvertimePayroll::create($overtime);

                                    }
                                    else if ($total > 7){

                                        $overtime = [
                                            'employee_id' => $overtime->employee_id,
                                            'date_overtime' => date('Y-m-d', strtotime($overtime->date_start)),
                                            'schedule_start_overtime' => date('H:i:s', strtotime($overtime->start_time)),
                                            'start_overtime' => date('H:i:s', strtotime($overtime->in_time)),
                                            'schedule_end_overtime' => date('H:i:s', strtotime($overtime->end_time)),
                                            'end_overtime' => date('H:i:s', strtotime($overtime->out_time)),

                                            'overtime_1' => '0',
                                            'amount_overtime_1' => '0',
                                            'total_overtime_1' => '0',

                                            'overtime_2' => '0',
                                            'amount_overtime_2' => '0',
                                            'total_overtime_2' => '0',

                                            'overtime_weekend_2' => '7',
                                            'amount_overtime_weekend_2' => floor(($salary*2)/173),
                                            'total_overtime_weekend_2' => floor((($salary*2)/173)*7),

                                            'overtime_weekend_3' => ($total)-7,
                                            'amount_overtime_weekend_3' => floor(($salary*3)/173),
                                            'total_overtime_weekend_3' => floor(($salary*3)/173)*(($total)-7),

                                            'total_overtime' => floor((($salary*2)/173)*7)+((($salary*3)/173)*(($total)-7)),
                                            'is_active' => '1',
                                        ];

                                        OvertimePayroll::create($overtime);

                                    }

                                }else if ($days == 'Weekday'){

                                    if ($total <= 1){

                                        $overtime = [
                                            'employee_id' => $overtime->employee_id,
                                            'date_overtime' => date('Y-m-d', strtotime($overtime->date_start)),
                                            'schedule_start_overtime' => date('H:i:s', strtotime($overtime->start_time)),
                                            'start_overtime' => date('H:i:s', strtotime($overtime->in_time)),
                                            'schedule_end_overtime' => date('H:i:s', strtotime($overtime->end_time)),
                                            'end_overtime' => date('H:i:s', strtotime($overtime->out_time)),

                                            'overtime_1' => $total,
                                            'amount_overtime_1' => floor(($salary*1.5)/173),
                                            'total_overtime_1' => floor($salary*1.5)/173*($total),

                                            'overtime_2' => '0',
                                            'amount_overtime_2' => '0',
                                            'total_overtime_2' => '0',

                                            'overtime_weekend_2' => '0',
                                            'amount_overtime_weekend_2' => '0',
                                            'total_overtime_weekend_2' => '0',

                                            'overtime_weekend_3' => '0',
                                            'amount_overtime_weekend_3' => '0',
                                            'total_overtime_weekend_3' => '0',

                                            'total_overtime' => floor($salary*1.5)/173*($total),
                                            'is_active' => '1',
                                        ];

                                        OvertimePayroll::create($overtime);

                                    }
                                    else

                                    if ($total > 1){

                                        $overtime = [
                                            'employee_id' => $overtime->employee_id,
                                            'date_overtime' => date('Y-m-d', strtotime($overtime->date_start)),
                                            'schedule_start_overtime' => date('H:i:s', strtotime($overtime->start_time)),
                                            'start_overtime' => date('H:i:s', strtotime($overtime->in_time)),
                                            'schedule_end_overtime' => date('H:i:s', strtotime($overtime->end_time)),
                                            'end_overtime' => date('H:i:s', strtotime($overtime->out_time)),

                                            'overtime_1' => '1',
                                            'amount_overtime_1' => floor(($salary*1.5)/173),
                                            'total_overtime_1' => floor($salary*1.5)/173,

                                            'overtime_2' => ($total)-1,
                                            'amount_overtime_2' => floor(($salary*2)/173),
                                            'total_overtime_2' => floor(($salary*2)/173)*(($total)-1),

                                            'overtime_weekend_2' => '0',
                                            'amount_overtime_weekend_2' => '0',
                                            'total_overtime_weekend_2' => '0',

                                            'overtime_weekend_3' => '0',
                                            'amount_overtime_weekend_3' => '0',
                                            'total_overtime_weekend_3' => '0',

                                            'total_overtime' => floor(($salary*1.5)/173)+((($salary*2)/173)*(($total)-1)),
                                            'is_active' => '1',
                                        ];

                                        OvertimePayroll::create($overtime);

                                    }
                                }
                            }
                        }
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
        $data = Model::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $data], Response::HTTP_OK);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
        ]);

        $data = Model::findOrFail($id);
        $data->update($request->all());
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

    public function getEmployee()
    {
        $employee = DB::select('select id, pin from employees where (is_active = 1) and (company_id = 1 or company_id = 2) and (office_hour_id != 999) and (pin IS NOT NULL)');

        return $employee;
    }

    public function checkDateAttendance($date)
    {
        $checkdate = DB::select('select id from attendances where date_work=:date',
        array('date' => $date));

        return $checkdate;
    }

    public function checkWeekEnd($date)
    {
        $checkdate = DB::select('select date_format(cast(:date AS date),"%w") AS week',
        array('date' => $date));

        return $checkdate;
    }

    public function checkHoliday($date)
    {
        $holiday = DB::select('SELECT id FROM public_holidays WHERE holiday_date = :holidaydate',
        array('holidaydate' => $date));

        return $holiday;
    }

    public function getTimeLog($pin, $date)
    {
        $timelog = DB::select('select date_format(min(a.scan_date), "%Y-%m-%d %T") as in_time,
        (select date_format(min(b.scan_date), "%Y-%m-%d %T") from att_logs b where (b.scan_date between (min(a.scan_date))
        and (select date_format(date_add(min(a.scan_date), interval 18 hour), "%Y/%m/%d %T")))
        and (b.sn in (:sn1, :sn2) or b.inoutmode = :inoutmode2) and (b.pin = :pin1)) as out_time
        FROM att_logs a where (a.sn in (:sn3, :sn4, :sn5) or a.inoutmode = :inoutmode1) and (a.pin = :pin2)
        and (date_format(a.scan_date, "%Y-%m-%d")=:date)',
        array('sn1' => '66680618291740','sn2' => '66595018220746', 'sn3' => '66680618291798', 'sn4' => '66680618291826', 'sn5' => '66595016290490',
        'inoutmode1' => '1', 'inoutmode2' => '2', 'pin1' => $pin, 'pin2' => $pin, 'date' => $date
        ));

        return $timelog;
    }

    public function getLeave($employeeid, $date)
    {
        $leave = DB::select('SELECT j.id, j.employee_id, j.start_date, j.end_date, k.name FROM leaves j INNER JOIN leave_categories k ON j.leave_category_id=k.id
        WHERE (j.employee_id = :employeeid) AND
        (:tanggal IN (select a.leave_date
        from (
            select curdate() - INTERVAL (a.a + (10 * b.a) + (100 * c.a) + (1000 * d.a) ) DAY as leave_date
            from (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as a
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as b
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as c
            cross join (select 0 as a union all select 1 union all select 2 union all select 3 union all select 4 union all select 5 union all select 6 union all select 7 union all select 8 union all select 9) as d
        ) a
        where a.leave_date BETWEEN (SELECT start_date FROM leaves WHERE id = j.id) and (SELECT end_date FROM leaves WHERE id = j.id)
        ))',
        array('employeeid' => $employeeid, 'tanggal'=>$date));

        return $leave;
    }

    // public function getAttendance($employeeid, $date)
    // {
    //     $attendance = DB::select('SELECT a.id, a.employee_id, b.pin, b.office_hour_id, a.in, a.out, b.payroll_type_id,
    //     cast(a.in AS date) AS date_work,
    //     cast(a.in AS TIME) AS in_time,

    //     (SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))) AS time_in,
    //     (SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
    //     (cast(a.in AS TIME)))) AS late_in,

    //     cast(a.out AS TIME) AS out_time,
    //     (SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR))) AS time_out,
    //     (SELECT TIMEDIFF((cast(a.out AS TIME)),(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR))))) AS late_out,

    //     TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
    //     (cast(a.in AS TIME))))) as late_in_count,
    //     TIME_TO_SEC((SELECT TIMEDIFF((cast(a.out AS TIME)),(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))))) as late_out_count,

    //     CASE
    //     WHEN
    //          date_format(cast(a.in AS date),"%w") = 0
    //     THEN :libur
    //     WHEN
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
    //         (cast(a.in AS TIME)))))) > 0)
    //         AND
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((cast(a.out AS TIME)),(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR))))))) > 0)
    //     THEN :tt
    //     WHEN
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((cast(a.out AS TIME)),(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR))))))) < -7200)
    //         AND (TIME_TO_SEC(cast((SELECT TIMEDIFF(a.out, a.in)) AS TIME)) > 18000)
    //         AND (b.payroll_type_id = 1)
    //     THEN :st
    //     WHEN
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((cast(a.out AS TIME)),(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR))))))) < -7200)
    //         AND (TIME_TO_SEC(cast((SELECT TIMEDIFF(a.out, a.in)) AS TIME)) < 18000)
    //         AND (b.payroll_type_id = 1)
    //     THEN :i1
    //     WHEN
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
    //         (cast(a.in AS TIME)))))) > 14400)
    //         AND (b.payroll_type_id = 1)
    //     THEN :i2
    //     WHEN
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
    //         (cast(a.in AS TIME)))))) < -7200)
    //         AND (b.payroll_type_id = 1)
    //     THEN :tl2
    //     WHEN
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
    //         (cast(a.in AS TIME)))))) > -7200)
    //         AND (b.payroll_type_id = 1)
    //     THEN :tk2

    //     WHEN
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
    //         (cast(a.in AS TIME)))))) < -900)
    //         AND (b.payroll_type_id = 2)
    //     THEN :i3
    //     WHEN
    //         ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
    //         AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
    //         (cast(a.in AS TIME)))))) > -900)
    //         AND (b.payroll_type_id = 2)
    //     THEN :tk3
    //     END AS attendance_status,

    //     cast((SELECT TIMEDIFF(a.out, a.in)) AS TIME) AS total_time_work,
    //     TIME_TO_SEC(cast((SELECT TIMEDIFF(a.out, a.in)) AS TIME)) AS count_time_work

    //     FROM time_logs a INNER JOIN employees b ON a.employee_id=b.id where (a.employee_id = :employeeid) and (date_format(a.in, "%Y-%m-%d")=:date)',
    //     array('tt'=>'Tidak Telat', 'st'=>'Setengah Hari', 'tl2'=>'Telat > 2 Jam', 'tk2'=>'Telat < 2 Jam', 'employeeid'=>$employeeid, 'date'=>$date,
    //     'libur'=>'Libur', 'i1'=>'Ijin', 'i2'=>'Ijin', 'i3'=>'Ijin', 'tk3'=>'Telat < 2 Jam'));

    //     return $attendance;
    // }

    public function getAttendance($employeeid, $date)
    {
        $attendance = DB::select("SELECT a.id, a.employee_id, b.pin, b.office_hour_id, a.in, a.out, b.payroll_type_id,
        cast(a.in AS date) AS date_work,
        cast(a.in AS TIME) AS in_time,

        (SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))) AS time_in,
        (SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
        (cast(a.in AS TIME)))) AS late_in,

        cast(a.out AS TIME) AS out_time,

		CASE
		WHEN
			(select date_format(cast(a.in AS date),'%w')) = 6
		THEN
			(SELECT weekend_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekend_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
		WHEN
			(select date_format(cast(a.in AS date),'%w') )!= 6
		THEN
			(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
		END AS time_out,

        (SELECT TIMEDIFF((cast(a.out AS TIME)),
		(CASE
		WHEN
			(select date_format(cast(a.in AS date),'%w')) = 6
		THEN
			(SELECT weekend_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekend_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
		WHEN
			(select date_format(cast(a.in AS date),'%w') )!= 6
		THEN
			(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
		END))) AS late_out,

        TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
        (cast(a.in AS TIME))))) as late_in_count,

        TIME_TO_SEC((SELECT TIMEDIFF((cast(a.out AS TIME)),
		(CASE
		WHEN
			(select date_format(cast(a.in AS date),'%w')) = 6
		THEN
			(SELECT weekend_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekend_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
		WHEN
			(select date_format(cast(a.in AS date),'%w') )!= 6
		THEN
			(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
		END)))) as late_out_count,

        CASE
        WHEN
             date_format(cast(a.in AS date),'%w') = 0
        THEN 'Libur'
        WHEN
            ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
            (cast(a.in AS TIME)))))) > 0)
            AND
            (TIME_TO_SEC((SELECT TIMEDIFF((cast(a.out AS TIME)),
			(CASE
			WHEN
				(select date_format(cast(a.in AS date),'%w')) = 6
			THEN
				(SELECT weekend_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
				AND (c.weekend_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
			WHEN
				(select date_format(cast(a.in AS date),'%w') )!= 6
			THEN
				(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
				AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
			END)))) > 0)
        THEN 'Tidak Telat'
        WHEN
			(TIME_TO_SEC((SELECT TIMEDIFF((cast(a.out AS TIME)),
			(CASE
			WHEN
				(select date_format(cast(a.in AS date),'%w')) = 6
			THEN
				(SELECT weekend_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
				AND (c.weekend_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
			WHEN
				(select date_format(cast(a.in AS date),'%w') )!= 6
			THEN
				(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
				AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
			END)))) > 18000)
            AND (b.payroll_type_id = 1)
        THEN 'Setengah Hari'
        WHEN
			(TIME_TO_SEC((SELECT TIMEDIFF((cast(a.out AS TIME)),
			(CASE
			WHEN
				(select date_format(cast(a.in AS date),'%w')) = 6
			THEN
				(SELECT weekend_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
				AND (c.weekend_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
			WHEN
				(select date_format(cast(a.in AS date),'%w') )!= 6
			THEN
				(SELECT weekday_out FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
				AND (c.weekday_out BETWEEN DATE_ADD(cast(a.out AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.out AS TIME), INTERVAL 5 HOUR)))
			END)))) < 18000)
            AND (b.payroll_type_id = 1)
        THEN 'Ijin'
        WHEN
            ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
            (cast(a.in AS TIME)))))) > 14400)
            AND (b.payroll_type_id = 1)
        THEN 'Ijin'
        WHEN
            ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
            (cast(a.in AS TIME)))))) < -7200)
            AND (b.payroll_type_id = 1)
        THEN 'Telat > 2 Jam'
        WHEN
            ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
            (cast(a.in AS TIME)))))) > -7200)
            AND (b.payroll_type_id = 1)
        THEN 'Telat < 2 Jam'

        WHEN
            ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
            (cast(a.in AS TIME)))))) < -900)
            AND (b.payroll_type_id = 2)
        THEN 'Ijin'
        WHEN
            ((TIME_TO_SEC((SELECT TIMEDIFF((SELECT weekday_in FROM office_hour_details c WHERE (c.office_hour_id = b.office_hour_id)
            AND (c.weekday_in BETWEEN DATE_ADD(cast(a.in AS TIME), INTERVAL -5 HOUR) AND DATE_ADD(cast(a.in AS TIME), INTERVAL 5 HOUR))),
            (cast(a.in AS TIME)))))) > -900)
            AND (b.payroll_type_id = 2)
        THEN 'Telat < 2 Jam'
        END AS attendance_status,

        cast((SELECT TIMEDIFF(a.out, a.in)) AS TIME) AS total_time_work,
        TIME_TO_SEC(cast((SELECT TIMEDIFF(a.out, a.in)) AS TIME)) AS count_time_work

        FROM time_logs a INNER JOIN employees b ON a.employee_id=b.id where (a.employee_id = :employeeid) and (date_format(a.in, '%Y-%m-%d')=:date)",
        array('employeeid'=>$employeeid, 'date'=>$date));

        return $attendance;
    }

    public function getOvertime($start, $end)
    {
        $overtime = DB::select('SELECT a.id, b.employee_id, c.pin, CAST(a.start_time AS date) AS date_start, CAST(a.end_time AS date) AS date_end, a.start_time, a.end_time,

        CASE
        WHEN
            ((select DATE_FORMAT(CAST(a.start_time AS date),"%w") != 0) AND (CAST(a.start_time AS date) NOT IN (SELECT holiday_date FROM public_holidays)))
        THEN
            :weekday
        WHEN
            ((select DATE_FORMAT(CAST(a.start_time AS date),"%w") = 0) OR (CAST(a.start_time AS date) IN (SELECT holiday_date FROM public_holidays)))
        THEN
            :weekend
        END AS days,

        (select date_format(min(d.scan_date), "%Y-%m-%d %T") as in_time FROM att_logs d where (d.sn IN
        (66680618291798, 66680618291826, 66595016290490)
        or d.inoutmode = 1) and (d.pin = c.pin) and (date_format(d.scan_date, "%Y-%m-%d")=CAST(a.start_time AS date))) AS in_time,

        (select (select date_format(max(b.scan_date), "%Y-%m-%d %T") from att_logs b where (b.scan_date between (min(a.scan_date))
        and (select date_format(date_add(min(a.scan_date), interval 18 hour), "%Y/%m/%d %T")))
        and (b.sn IN (66680618291740, 66595018220746) or b.inoutmode = 2) and (b.pin = c.pin)) as out_time
        FROM att_logs a where (a.sn in (66680618291798, 66680618291826, 66595016290490)
        or a.inoutmode = 1) and (a.pin = c.pin)
        and (date_format(a.scan_date, "%Y-%m-%d")=CAST(a.start_time AS date))) AS out_time,

        CASE
        WHEN
            ((select date_format(min(d.scan_date), "%Y-%m-%d %T") as in_time FROM att_logs d where (d.sn IN
            (66680618291798, 66680618291826, 66595016290490)
            or d.inoutmode = 1) and (d.pin = c.pin) and (date_format(d.scan_date, "%Y-%m-%d")=CAST(a.start_time AS DATE)))) < (a.start_time)
            AND
            ((select (select date_format(max(b.scan_date), "%Y-%m-%d %T") from att_logs b where (b.scan_date between (min(a.scan_date))
            and (select date_format(date_add(min(a.scan_date), interval 18 hour), "%Y/%m/%d %T")))
            and (b.sn IN (66680618291740, 66595018220746) or b.inoutmode = 2) and (b.pin = c.pin)) as out_time
            FROM att_logs a where (a.sn in (66680618291798, 66680618291826, 66595016290490)
            or a.inoutmode = 1) and (a.pin = c.pin)
            and (date_format(a.scan_date, "%Y-%m-%d")=CAST(a.start_time AS DATE)))) > (a.end_time)
            AND
            ((select DATE_FORMAT(CAST(a.start_time AS date),"%w") = 0) OR (CAST(a.start_time AS DATE) IN (SELECT holiday_date FROM public_holidays)))
        THEN
            CAST(TIME_TO_SEC(TIMEDIFF(a.end_time, a.start_time))/3600 AS UNSIGNED)
        WHEN
            ((select date_format(min(d.scan_date), "%Y-%m-%d %T") as in_time FROM att_logs d where (d.sn IN
            (66680618291798, 66680618291826, 66595016290490)
            or d.inoutmode = 1) and (d.pin = c.pin) and (date_format(d.scan_date, "%Y-%m-%d")=CAST(a.start_time AS DATE)))) < (a.start_time)
            AND
            ((select (select date_format(max(b.scan_date), "%Y-%m-%d %T") from att_logs b where (b.scan_date between (min(a.scan_date))
            and (select date_format(date_add(min(a.scan_date), interval 18 hour), "%Y/%m/%d %T")))
            and (b.sn IN (66680618291740, 66595018220746) or b.inoutmode = 2) and (b.pin = c.pin)) as out_time
            FROM att_logs a where (a.sn in (66680618291798, 66680618291826, 66595016290490)
            or a.inoutmode = 1) and (a.pin = c.pin)
            and (date_format(a.scan_date, "%Y-%m-%d")=CAST(a.start_time AS DATE)))) > (a.end_time)
            AND
            ((select DATE_FORMAT(CAST(a.start_time AS date),"%w") != 0) OR (CAST(a.start_time AS DATE) NOT IN (SELECT holiday_date FROM public_holidays)))
        THEN
            CAST(TIME_TO_SEC(TIMEDIFF(a.end_time, a.start_time))/3600 AS UNSIGNED)

        END AS total_overtime

        FROM overtimes a INNER JOIN overtime_employees b ON a.id=b.overtime_id INNER JOIN employees c ON b.employee_id=c.id
        WHERE (b.approval_status_id <> 1) AND ISNULL(a.deleted_at) AND ISNULL(b.deleted_at)  AND (CAST(a.start_time AS DATE) BETWEEN :start AND :end)',
        array('weekday' => 'Weekday', 'weekend'=>'Weekend', 'start'=>$start, 'end'=>$end));

        return $overtime;
    }

    public function getSalaryType($employeeid)
    {
        $salarytype = DB::select('SELECT * FROM employees WHERE id = :id AND salary_type_id = (SELECT id FROM salary_types WHERE NAME = :overtime)',
        array('overtime'=>'Overtime', 'id'=>$employeeid));

        return $salarytype;
    }

    public function getBasicSaving()
    {
        $basicsaving = DB::select('SELECT amount FROM basic_savings ORDER BY id DESC LIMIT 1');

        return $basicsaving;
    }

    public function getAttLog($start, $end)
    {
        $attlog = DB::connection('sqlsrv')->select('select sn, cast(scan_date as datetime) as scan_date, pin, verifymode, inoutmode, reserved, work_code, att_id
        from att_log where cast(scan_date as date) between :start and :end order by scan_date desc',
        array('start'=>$start, 'end'=>$end));

        return $attlog;
    }

    public function checkAttLog($pin, $scan_date)
    {
        $check = DB::select('SELECT * FROM att_logs WHERE pin = :pin AND scan_date = :scan_date',
        array('pin'=>$pin, 'scan_date'=>$scan_date));

        return $check;
    }

}
