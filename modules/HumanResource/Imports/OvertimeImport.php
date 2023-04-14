<?php

namespace Modules\HumanResource\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\HumanResource\Models\DummyAttendance;
use Modules\HumanResource\Models\Employee;
use Modules\HumanResource\Models\Overtime;
use Modules\HumanResource\Models\OvertimeShiftEmployee;
use Modules\HumanResource\Models\TemporaryOvertime;

class OvertimeImport implements ToCollection, WithHeadingRow
{
    public function __construct($approved_by, $fileName)
    {
        $this->approved_by = $approved_by;
        $this->fileName = $fileName;
    }

    public function collection(Collection $collection)
    {
        $rules = [
            '*.nik' => 'required',
            '*.request_date' => 'required',
            '*.description' => 'required',
        ];
        foreach ($collection as $col) {
            if (empty($col['overtime_before_duration']) && empty($col['overtime_after_duration'])) {
                $rules += ['*.duration' => 'required'];
            }

            if (!empty($col['end_time_holiday'])) $rules += ['*.start_time_holiday' => 'required'];

            if (!empty($col['start_time_holiday']))  $rules += ['*.end_time_holiday' => 'required'];
        }
        Validator::make($collection->toArray(), $rules)->validate();

        TemporaryOvertime::truncate();

        foreach ($collection as $col) {
            $requestDate = $this->convertDateOrTime($col['request_date'], 'date');

            $employee_id = Employee::where('nik', $col['nik'])->orWhere('old_nik', $col['nik'])->pluck('id')->first();

            $shift = DummyAttendance::whereDate('scan_in', $requestDate)->where('employee_code', $col['nik'])->first();

            if ($employee_id && $shift) {
                $listYes = ['Yes', 'yes', 'Y', 'y', 'Ya', 'ya', 1, "1"];
                $resultTotalTime = $this->calculateTotalTime($col['overtime_before_duration'], $col['overtime_after_duration']);
                $without_reducing_rest_hours = (!empty($col['without_reducing_rest_hours']) && in_array($col['without_reducing_rest_hours'], $listYes, true)) ? 1 : 0;
                $is_holiday = (!empty($col['is_holiday']) && in_array($col['is_holiday'], $listYes, true)) ? 1 : 0;
                $is_saturday = (!empty($col['is_saturday']) && in_array($col['is_saturday'], $listYes, true)) ? 1 : 0;

                TemporaryOvertime::create([
                    'employee_id' => $employee_id,
                    'approved_by' => $this->approved_by,
                    'request_date' => $requestDate,
                    'overtime_before_duration' => $col['overtime_before_duration'],
                    'overtime_after_duration' => $col['overtime_after_duration'],
                    'total_time' => $resultTotalTime,
                    'attachment' => $this->fileName,
                    'description' => $col['description'],
                    'shift_id' => $shift->shift_id,
                    'schedule_in' => $shift->scan_in,
                    'schedule_out' => $shift->scan_out,
                    'without_reducing_rest_hours' => $without_reducing_rest_hours,
                    'is_holiday' => $is_holiday,
                    'is_saturday' => $is_saturday,
                    'start_time_holiday' => $col['start_time_holiday'],
                    'end_time_holiday' => $col['end_time_holiday']
                ]);
            }
        }
    }

    private function convertDateOrTime($value, $type)
    {
        $format = $type === 'date' ? 'Y-m-d' : 'H:i:s';
        $convert = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value))->format($format);
        return $convert;
    }

    private function calculateTotalTime($startTime, $endTime)
    {
        $time = [$startTime, $endTime];
        $checkIndexNull = [];

        foreach ($time as $key => $value) {
            if ($value == null) {
                array_push($checkIndexNull, $key);
            }
        }

        $start = "00:00";
        $end = "00:00";

        if (count($checkIndexNull) == 0) {
            $start = $startTime;
            $end = $endTime;
        } else {
            if ($checkIndexNull[0] == 0) {
                $end = $endTime;
            } else {
                $start = $startTime;
            }
        }

        $startHour = (int)explode(":", $start)[0];
        $endHour = (int)explode(":", $end)[0];

        $startMinutes = (int)explode(":", $start)[1];
        $endMinutes = (int)explode(":", $end)[1];

        $totalHour = $startHour + $endHour;
        $totalMinutes = $startMinutes + $endMinutes;

        if ($totalMinutes == 60) {
            $totalHour += 1;
            $totalMinutes = 0;
        }

        return $totalHour . '.' . $totalMinutes;
    }
}
