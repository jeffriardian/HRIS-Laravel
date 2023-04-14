<?php

namespace Modules\HumanResource\Services;

use DateTime;
use Modules\HumanResource\Models\AutoOvertimeRate;
use Modules\HumanResource\Models\ConsecutiveHoliday;
use Modules\HumanResource\Models\DummyAttendance;
use Modules\HumanResource\Models\Employee;
use Modules\HumanResource\Models\EmployeeOfficeHour;
use Modules\HumanResource\Models\EmployeeSalary;
use Modules\HumanResource\Models\ExchangeOfficeHour;
use Modules\HumanResource\Models\OvertimeRates;
use Modules\HumanResource\Models\PublicHoliday;

class CalculateOvertimeServiceV2
{
    private $basicSalary;
    private $overtime;

    public function autoCalculateHourlyOvertime()
    {
        // $scanIn = now()->format('Y-m-d');
        $scanIn = "2020-10-10";
        $nikFromOvertime = AutoOvertimeRate::with('employee')->whereDate('date', $scanIn)->get()->pluck('employee.nik');
        $attendances = DummyAttendance::with('employee.officeHours', 'shift')->whereDate('scan_in', $scanIn)->whereNotNull('scan_out')->whereNotIn('employee_code', $nikFromOvertime)->get();
        foreach ($attendances as $attendance) {
            $holidayAndSaturday = $this->getHolidayAndSaturday($scanIn, $attendance);
            if (!$holidayAndSaturday['holiday']) {
                $this->overtime = (object)[
                    'is_holiday' => $holidayAndSaturday['holiday'],
                    'is_saturday' => $holidayAndSaturday['saturday'],
                    'request_date' => $scanIn,
                    'total_time' => !is_null($attendance->employee->auto_overtime) ? $attendance->employee->auto_overtime : 0,
                    'start_time_holiday' => null,
                    'end_time_holiday' => null,
                    'without_reducing_rest_hours' => null,
                    'overtime_before_duration' => null,
                    'overtime_after_duration' => null
                ];
                $this->basicSalary = $this->getBasicSalary($attendance->employee->id);

                $realOvertime = $this->getRealOvertimeHoursCompareWithAttendance($attendance->employee->nik, $attendance->employee->payroll_type_id);
                $break = $this->getTotalBreak($realOvertime['totalOvertime']);
                $autoOvertimeWithoutSPLMinusBreak = $this->subtractHours($realOvertime['totalOvertime'], $this->convertBreakHourToHours($break));
                $total = $this->additionHours($realOvertime['autoOvertime'], $autoOvertimeWithoutSPLMinusBreak);

                $L1 = $L2 = $L3 = 0;
                $ratesL1 = $ratesL2 = $ratesL3 = 0;

                if ($attendance->employee->payroll_type_id == 1) {
                    $L1 = ($total > 1) ? 1 :  $this->convertHoursToDecimal($total, $attendance->employee->payroll_type_id);
                    $L2 = ($total > 1) ? $this->convertHoursToDecimal($total, $attendance->employee->payroll_type_id) - $L1 : 0;
                } else { // jika employee dengan gaji mingguan
                    // jika operator SMM
                    if ($attendance->employee->company_id != 3 && $attendance->employee->company_id != 4) {
                        $L1 = ($total > 1) ? 1 : 0.5;
                        $ratesL1 = round($this->getBasicL1Hours() * $L1);

                        $L2 = ($L1 == 0.5) ? 0 : $this->convertHoursToDecimal($total, $attendance->employee->payroll_type_id) - $L1;
                        $ratesL2 = round($this->getBasicL2Hours() * $L2);
                    } else { // jika operator OSI
                        $L1 = $this->convertHoursToDecimal($total, $attendance->employee->payroll_type_id);

                        $ratesL1 = round($this->getBasicL1Hours() * $L1);
                    }
                }

                // dd("L1 = $L1, Rates = $ratesL1", "L2 = $L2, Rates = $ratesL2", "L3 = $L3, Rates = $ratesL3");

                AutoOvertimeRate::updateOrCreate([
                    'employee_id' => $attendance->employee->id,
                    'date' => $scanIn
                ], [
                    'total_times' => $total,
                    'L1' => $L1,
                    'rates_L1' => $ratesL1,
                    'L2' => $L2,
                    'rates_L2' => $ratesL2,
                    'L3' => $L3,
                    'rates_L3' => $ratesL3,
                    'total_rates' => $ratesL1 + $ratesL2 + $ratesL3
                ]);
            }
        }
    }

    public function calculateHourlyOvertimeAndRatesBySPL($overtime)
    {
        $this->overtime = $overtime;
        $employee = Employee::whereId($this->overtime->employee_id)->firstOrFail();

        $this->basicSalary = $this->getBasicSalary($employee->id);

        $realOvertime = $this->getRealOvertimeHoursCompareWithAttendance($employee->nik, $employee->payroll_type_id);

        $L1 = $L2 = $L3 = 0;
        $ratesL1 = $ratesL2 = $ratesL3 = 0;

        $totalOvertime = $realOvertime['totalOvertime'];
        $autoOvertime = $realOvertime['autoOvertime'];
        $break = $this->getTotalBreak($totalOvertime);
        // dd("Overtime = $totalOvertime, Auto OVertime = $autoOvertime, Break = $break");

        // jika tipe gaji karyawan adalah overtime
        if ($employee->salary_type_id == 2) {
            if ((bool) $overtime->is_holiday) {
                $defaultL2Hours = 8; // 8 jam
                $defaultL3Hours = 0.5; // 30 menit

                if ($totalOvertime < $defaultL2Hours) {
                    $L2 = $this->subtractHours($totalOvertime, $break);
                    $L2 = $this->convertHoursToDecimal(number_format($L2, 2), $employee->payroll_type_id);
                    if ($employee->payroll_type_id == 2) $ratesL2 = round($this->getBasicL2Hours() * $L2);
                } else {
                    $multiple = floor($totalOvertime / $defaultL2Hours);
                    $L3 = $defaultL3Hours * $multiple;
                    $L2 =  $this->subtractHours($totalOvertime, $this->convertMinutesToHours($L3 * 60)) - $this->convertBreakHourToHours($break);
                    $L2 = $this->convertHoursToDecimal(number_format($L2, 2), $employee->payroll_type_id);
                    if ($employee->payroll_type_id == 2) {
                        $ratesL2 = round($this->getBasicL2Hours() * $L2);
                        $ratesL3 = round($this->getBasicL3Hours() * $L3);
                    }
                }
            } else {
                $totalMinusBreak = $this->subtractHours($totalOvertime, $this->convertBreakHourToHours($break));
                $total = $this->additionHours($totalMinusBreak, $autoOvertime);
                // jika employee dengan gaji bulanan
                if ($employee->payroll_type_id == 1) {
                    $L1 = ($total > 1) ? 1 :  $this->convertHoursToDecimal($total, $employee->payroll_type_id);
                    $L2 = ($total > 1) ? $this->convertHoursToDecimal($total, $employee->payroll_type_id) - $L1 : 0;
                } else { // jika employee dengan gaji mingguan
                    // jika operator SMM
                    if ($employee->company_id != 3 && $employee->company_id != 4) {
                        $L1 = ($total > 1) ? 1 : 0.5;
                        $ratesL1 = round($this->getBasicL1Hours() * $L1);

                        $L2 = ($L1 == 0.5) ? 0 : $this->convertHoursToDecimal($total, $employee->payroll_type_id) - $L1;
                        $ratesL2 = round($this->getBasicL2Hours() * $L2);
                    } else { // jika operator OSI
                        $L1 = $this->convertHoursToDecimal($total, $employee->payroll_type_id);

                        $ratesL1 = round($this->getBasicL1Hours() * $L1);
                    }
                }
            }
        }
        // dd("L1 = $L1, Rates = $ratesL1", "L2 = $L2, Rates = $ratesL2", "L3 = $L3, Rates = $ratesL3");
        OvertimeRates::updateOrCreate([
            'overtime_id' => $overtime->id
        ], [
            'L1' => $L1,
            'rates_L1' => $ratesL1,
            'L2' => $L2,
            'rates_L2' => $ratesL2,
            'L3' => $L3,
            'rates_L3' => $ratesL3,
            'total_rates' => $ratesL1 + $ratesL2 + $ratesL3
        ]);
    }

    private function getRealOvertimeHoursCompareWithAttendance($nik, $payrollType)
    {
        $attendance = DummyAttendance::with('shift')->where('employee_code', $nik)->whereDate('scan_in', $this->overtime->request_date)->firstOrFail();

        // total jumlah permintaan lembur
        $requestOvertime = $this->overtime->total_time;
        // get scan in karyawan dari absensi
        $scanIn = new DateTime($attendance->scan_in);
        // get scan out karyawan dari absensi
        $scanOut = new DateTime($attendance->scan_out);
        // get interval absensi dari scan in sampa scan out
        $intervalScanInScanOut = $scanOut->diff($scanIn)->format('%H.%I');
        // jadwal masuk
        $scheduleIn = $this->getScheduleIn($attendance);
        // keterlambatan (return minutes)
        $letness = $this->getLetness($scanIn, $scheduleIn);
        // wajib kerja
        $effectiveHours = $this->getEffectiveHours($payrollType);
        // default auto lembur
        $autoOvertime = $this->getDefaultAutoOvertime($payrollType);

        if ((bool) $this->overtime->without_reducing_rest_hours) {
            if ($intervalScanInScanOut < $requestOvertime) $requestOvertime = $this->reduceOvertime($intervalScanInScanOut, $payrollType);
            $autoOvertime = 0;
        } else {
            if ((bool) $this->overtime->is_holiday) {
                // jika tidak ada jam awal ketika hari libur
                if (is_null($this->overtime->start_time_holiday)) {
                    // jika interval scan lebih kecil dari permintaan lembur maka permintaan lembur = interval scan
                    if ($intervalScanInScanOut < $requestOvertime) $requestOvertime = $this->reduceOvertime($intervalScanInScanOut, $payrollType);
                } else {
                    // jika tidak terlambat
                    if ($letness <= 0) {
                        // maka interval scan dikurang keterlambatan
                        $interval = $intervalScanInScanOut - $this->convertMinutesToHours($letness);
                        // jika interval lebih kecil dari wajib kerja, maka permintaan lembur = interval
                        if ($interval < $effectiveHours) $requestOvertime = $interval;
                    } else {
                        // jika terlambat jumlah overtime dikurang 30 menit sebagai punishment
                        $letCut = number_format(0.30, 2);
                        // jika interval scan lebih besar dari jam wajib kerja
                        if ($intervalScanInScanOut > $effectiveHours) {
                            // maka permintaan lembur = (jam wajib kerja dikurang 30 menit) 
                            $requestOvertime = $this->subtractHours($effectiveHours, $letCut);
                        } else { // jika interval scan lebih kecil dari jam wajib kerja
                            // maka permintaan lembur = (interval scan dikurang 30 menit) 
                            $requestOvertime = $this->subtractHours($intervalScanInScanOut, $letCut);
                        }
                    }
                    // permintaan lembur di kurangi sesuai dengan total lembur
                    // contoh : total lembur 1 jam 20 menit menjadi 1 jam
                    // contoh : total lembur 1 jam 55 menit menjadi 2 jam
                    $requestOvertime = $this->reduceOvertime($requestOvertime, $payrollType);
                }
            } else {
                // jika interval scan kurang dari 8 jam
                if ($intervalScanInScanOut <= 8) {
                    // maka permintaan menjadi 0
                    $requestOvertime = 0;
                } else {
                    // jika tidak terlambat
                    if ($letness <= 0) {
                        // jika lembur sebelum jam masuk dan tidak ada jam lembur setelah
                        if (!is_null($this->overtime->overtime_before_duration) && is_null($this->overtime->overtime_after_duration)) {
                            $convertLetness = $this->convertMinutesToHours($letness);
                            $split = explode(":", $this->overtime->overtime_before_duration);
                            $hours = "$split[0].$split[1]";
                            $minusOvertime = $this->subtractHours($intervalScanInScanOut, $requestOvertime);
                            if ($intervalScanInScanOut < 8) {
                                $requestOvertime = $this->subtractHours("08.00", $minusOvertime);
                                $requestOvertime = $this->reduceOvertime($requestOvertime, $payrollType);
                            } else {
                                if ($convertLetness < $hours) $requestOvertime = $this->reduceOvertime($convertLetness, $payrollType);
                            }
                            // jika lembur sebelum jam masuk dan  ada jam lembur setelah
                        } elseif (!is_null($this->overtime->overtime_before_duration) && !is_null($this->overtime->overtime_after_duration)) {
                            if ($intervalScanInScanOut > 8) {
                                $requestOvertime = $this->subtractHours($intervalScanInScanOut, "08.00");
                                $requestOvertime = ($requestOvertime > $this->overtime->total_time) ? $this->overtime->total_time : $this->reduceOvertime($requestOvertime, $payrollType);
                            } else {
                                $requestOvertime = 0;
                            }
                        } else { // jika tidak lembur sebelum jam masuk
                            $total = $this->subtractHours($intervalScanInScanOut, $this->convertMinutesToHours($letness));
                            if ($total > 8) {
                                $totalExcessHoursWorked = number_format($total - 8, 2);
                                if ($totalExcessHoursWorked < $requestOvertime) $requestOvertime = $this->reduceOvertime($totalExcessHoursWorked, $payrollType);
                            } else {
                                $requestOvertime = 0;
                            }
                        }
                    } else {
                        $totalOvertime = $this->subtractHours($intervalScanInScanOut, number_format($this->overtime->total_time, 2));
                        if ($totalOvertime < number_format($this->overtime->total_time, 2)) $requestOvertime = $this->reduceOvertime($totalOvertime, $payrollType);
                    }
                }

                $autoOvertime = $this->totalDefaultAutoOvertime($intervalScanInScanOut, $effectiveHours, $letness, $payrollType);
                $autoOvertime = number_format($autoOvertime, 2);

                if ($letness > 0) {
                    $letCut = 0;
                    $multiple = floor($letness / 30);
                    for ($i = 0; $i <= $multiple; $i++) {
                        $letCut += 30;
                    }
                    $letCut = $this->convertMinutesToHours($letCut);
                    if ($autoOvertime < $letCut) {
                        $sisa = $this->subtractHours($letCut, $autoOvertime);
                        $autoOvertime = 0;
                        $requestOvertime = (number_format($requestOvertime, 2) < $sisa) ? 0 : $this->subtractHours(number_format($requestOvertime, 2), $sisa);
                    } else {
                        $autoOvertime = $this->subtractHours($autoOvertime, $letCut);
                    }
                }
            }
        }
        // dd(number_format($requestOvertime, 2), $letness, number_format($autoOvertime, 2), number_format($break, 2));
        return ['totalOvertime' => number_format($requestOvertime, 2), 'autoOvertime' => number_format($autoOvertime, 2)];
    }

    private function getScheduleIn($attendance)
    {
        // mendapatkan jam wajib kerja dan jadwal jam masuk
        if ((bool)$this->overtime->is_holiday) { // jika di hari libur karyawan atau di tanggal merah
            // jika tidak ada jam awal
            if (is_null($this->overtime->start_time_holiday)) {
                // maka schedule in di dapat dari scan in
                $result = $attendance->scan_in;
            } else { // jika ada jadwal masuk
                $result = (new DateTime($this->overtime->start_time_holiday))->format('H:i:s');
            }
        } else { // jika bukan di tanggal merah
            // jika di hari sabtu
            if ((bool) $this->overtime->is_saturday) {
                // schedule in di dapat dari shift weekend in
                $result = $attendance->shift->weekend_in; // jadwal employee pada jam masuk
            } else { // jika di hari sabtu
                // schedule in di dapat dari shift weekday in
                $result = $attendance->shift->weekday_in; // jadwal employee pada jam masuk
            }
        }

        return $result;
    }

    private function getLetness($scanIn, $scheduleIn)
    {
        // get waktu terlambat masuk
        // jika di hari libur & tidak ada jam masuk
        if (is_null($this->overtime->start_time_holiday) && (bool)$this->overtime->is_holiday) {
            $result = 0;
        } else {
            $result = (strtotime($scanIn->format('H:i:s')) - strtotime($scheduleIn)) / 60;
        }
        return $result;
    }

    private function getEffectiveHours($payrollType)
    {
        if ((bool)$this->overtime->is_holiday) {
            $result = 0; // wajib kerja
            if (!is_null($this->overtime->start_time_holiday)) {
                $startTime = new DateTime($this->overtime->start_time_holiday);
                $endTime = new DateTime($this->overtime->end_time_holiday);
                $result = $endTime->diff($startTime)->format('%H.%I');
            }
        } else {
            if ($payrollType == 1) {
                $result = number_format(6.10, 2);
            } else {
                $result = ((bool) $this->overtime->is_saturday) ? 5 : 7;
            }
        }

        return $result;
    }

    private function getDefaultAutoOvertime($payrollType)
    {
        // jika di hari libur maka auto overtime tidak ada
        if ((bool)$this->overtime->is_holiday) {
            $result =  0;
        } else {
            // jika tipe paroll bulanan maka auto overtime = 1 jam 20 menit
            if ($payrollType == 1) {
                $result =  1.20;
            } else { // jika tipe paroll mingguan maka auto overtime = 2 jam 20 menit di hari sabtu dan 30 menit di hari senin - jumat
                $result = ((bool) $this->overtime->is_saturday) ? 2.30 : 0.30;
            }
        }
        return $result;
    }

    private function convertMinutesToHours($minutes)
    {
        if (abs($minutes) > 59) {
            $sisa = abs($minutes) % 60;
            $hours = intval(abs($minutes) / 60);
            $result = (strlen((string)abs($sisa)) > 1) ? "$hours.$sisa" : "$hours.0$sisa";
        } else {
            $result = (strlen((string)abs($minutes)) > 1) ? "0." . abs($minutes) : "0.0" . abs($minutes);
        }
        return $result;
    }

    private function getTotalBreak($requestOvertime)
    {
        $totalTime = $requestOvertime;
        $totalBreak = 0;
        $break = 5;

        $multiple = floor($totalTime / 8); // jumlah kelipatan dari total overtime

        for ($i = 0; $i <= $multiple; $i++) {
            if ($totalTime >= $break) $totalBreak += 0.5;
            $break += 7;
        }
        return $totalBreak;
    }

    private function totalDefaultAutoOvertime($interval, $effectiveHours, $letness, $payrollType)
    {
        if (!(bool) $this->overtime->is_holiday) {
            $result = $this->getDefaultAutoOvertime($payrollType);

            if ($letness <= 0) {
                if (is_null($this->overtime->overtime_before_duration)) {
                    $convertLetness = $this->convertMinutesToHours($letness);
                    $intervalScan = number_format($this->subtractHours($interval, $convertLetness), 2);
                } else {
                    $intervalScan = $interval;
                }
            } else {
                $intervalScan = $interval;
            }


            if ($intervalScan > $effectiveHours && $intervalScan < 8) {
                $effectiveHoursPlusBreak = number_format($effectiveHours + 0.30, 2);
                // jika tidak terlambat
                if ($letness <= 0) {
                    if ($intervalScan <= $effectiveHoursPlusBreak) $result = 0;

                    if ($intervalScan > $effectiveHoursPlusBreak && $intervalScan < 8) {
                        if ($payrollType == 1) {
                            $splitInterval = explode('.', $intervalScan);
                            $result = ($splitInterval[0] > 6) ? 20 + $splitInterval[1] : $splitInterval[1] - 40;
                            $result = $this->convertMinutesToHours($result);
                        } else {
                            if ((bool) $this->overtime->is_saturday) {
                                $totalExcessEffectiveWorkingHours = number_format($intervalScan - $effectiveHoursPlusBreak, 2) * 60;
                                $multiple = floor(floor($totalExcessEffectiveWorkingHours) / 30);
                                $total = 0;
                                for ($i = 0; $i < $multiple; $i++) {
                                    $total += 0.5;
                                }
                                $convert = explode('.', $total);
                                if (count($convert) > 1) $total = "$convert[0].30";
                                $result = $total;
                            } else {
                                $result = 0;
                            }
                        }
                    }
                } else {
                    if ($intervalScan <= $effectiveHoursPlusBreak) {
                        $result = 0;
                    } else {
                        if ($payrollType == 1) {
                            $splitInterval = explode(".", $intervalScan);
                            $splitMandatory = explode(".", $effectiveHoursPlusBreak);
                            if ($splitInterval[0] == $splitMandatory[0]) {
                                $result = $this->convertMinutesToHours($splitInterval[1] - $splitMandatory[1]);
                            } else {
                                if (count($splitInterval) > 1) {
                                    $result = $this->convertMinutesToHours(20 + $splitInterval[1]);
                                    if ($result > 1.20) $result = 1.20;
                                } else {
                                    $result = number_format(0.20, 2);
                                }
                            }
                        } else {
                            $totalExcessEffectiveWorkingHours = number_format($intervalScan - $effectiveHoursPlusBreak, 2) * 60;
                            $multiple = floor(floor($totalExcessEffectiveWorkingHours) / 30);
                            $result = 0;
                            for ($i = 0; $i < $multiple; $i++) {
                                $result += 0.5;
                            }
                            $c = explode('.', $result);
                            if (count($c) > 1) $result = "$c[0].30";
                        }
                    }
                }
            }

            if ($intervalScan < $effectiveHours) $result = 0;
            return $result;
        }
    }

    private function additionHours($hour1, $hour2)
    {
        $split = explode(".", $hour2);
        $hour = $split[0];
        $minutes = (isset($split[1])) ? $split[1] : 0;
        return date('H.i', strtotime("+$hour hour +$minutes minutes", strtotime($hour1)));
    }

    private function subtractHours($hour1, $hour2)
    {
        $split = explode(".", $hour2);
        $hour = $split[0];
        $minutes = (isset($split[1])) ? $split[1] : 0;
        return date('H.i', strtotime("-$hour hour -$minutes minutes", strtotime($hour1)));
    }

    private function reduceOvertime($overtime, $payrollType)
    {
        if ($payrollType == 1) {
            return $overtime;
        } else {
            $split = explode(".", $overtime);
            $hours = (int) $split[0];
            $minutes = 0;
            if (count($split) > 1) $minutes = (strlen($split[1] == 1)) ? (int)"$split[1].0" : (int)$split[1];

            if ($minutes < 30) {
                if ($minutes < 25) $minutes = 0;

                if ($minutes >= 25 && $minutes <= 30) $minutes = 30;
            } else {
                if ($minutes < 55 && $minutes > 30) $minutes = 30;

                if ($minutes >= 55 && $minutes <= 59) {
                    $hours++;
                    $minutes = 0;
                }
            }
            return number_format("$hours.$minutes", 2);
        }
    }

    private function convertHoursToDecimal($hours, $payrollType)
    {
        $splitHour = explode(".", $hours);
        $hour = $splitHour[0];
        $minutes = 0;
        if ($payrollType == 1) {
            if (count($splitHour) > 1) {
                $minutes = substr(number_format($splitHour[1] / 60, 2), 2);
            }
        } else {
            if (count($splitHour) > 1) {
                if ($splitHour[1] == 30 || $splitHour[1] == 3) $minutes = 5;
            }
        }
        return "$hour.$minutes";
    }

    private function convertBreakHourToHours($hours)
    {
        $splitHour = explode(".", $hours);
        $hour = $splitHour[0];
        $minutes = 0;
        if (count($splitHour) > 1) {
            if ($splitHour[1] == 5) $minutes = 30;
        }
        return "$hour.$minutes";
    }

    private function getBasicSalary($employeeId)
    {
        return 3145428;
        // return EmployeeSalary::where(['employee_id' => $employeeId, 'payroll_component_id' => 1])->pluck('amount')[0];
    }

    private function getBasicL1Hours()
    {
        return $this->getOvertimePerHour($this->basicSalary, 'L1');
    }

    private function getBasicL2Hours()
    {
        return $this->getOvertimePerHour($this->basicSalary, 'L2');
    }

    private function getBasicL3Hours()
    {
        return $this->getOvertimePerHour($this->basicSalary, 'L3');
    }

    private function getOvertimePerHour($basicSalary, $overtimeType)
    {
        $hour = 0;
        switch ($overtimeType) {
            case 'L2':
                $hour = 2;
                break;

            case 'L3':
                $hour = 3;
                break;

            default:
                $hour = 1.5;
                break;
        }

        return round(($basicSalary * $hour) / 173);
    }

    private function getHolidayAndSaturday($date, $attendance)
    {
        $holiday = $saturday = 0;

        $publicHoliday = PublicHoliday::where('holiday_date', $date)->exists();
        if ($publicHoliday) {
            $holiday = (int) $publicHoliday;
        } else {
            // nama hari
            $today = date('l', strtotime($date));
            // mengambil tanggal 2 minggu kemarin
            $twoWeeksAgo = date('Y-m-d', strtotime("-14 day", strtotime($date)));

            $consecutiveHoliday = ConsecutiveHoliday::where('employee_id', $attendance->employee->id)->first();

            // mendapatkan data absensi antara 2 minggu kemarin sampai permintaan tanggal
            // tanpa mengambil tanggal di hari sabtu atau minggu
            $attendanceFromTwoWeeksAgo = DummyAttendance::with(['shift', 'employee.officeHours'])
                ->where('employee_code', $attendance->employee->nik)
                ->whereBetween('scan_in', [$twoWeeksAgo, $date])
                ->orderBy('scan_in', 'ASC')
                ->get()
                ->filter(function ($value) {
                    $dayName = date('l', strtotime($value->scan_in));
                    return $dayName != 'Saturday' && $dayName != 'Sunday';
                });

            $attendanceShift = $attendanceFromTwoWeeksAgo->pluck('shift_id')->unique()->values()->toArray();

            $employeeOfficeHours = $attendance->employee->officeHours->pluck('office_hour_id')->toArray();

            $weekendEmployeeOfficeHours = $attendance->employee->officeHours->pluck('weekend', 'office_hour_id')->toArray();


            $listHolidays = ['Friday' => 'Saturday', 'Saturday' => 'Sunday'];

            // dd($attendanceShift, $employeeOfficeHours, $weekendEmployeeOfficeHours);

            if (!is_null($consecutiveHoliday)) {
                if (count($attendanceShift) == 0) {
                    if ($attendance->employee->join_at == $date) {
                        $weekend = $weekendEmployeeOfficeHours[$attendance->shift_id];
                        if ($weekend == $today) $saturday = 1;

                        if ($today == $listHolidays[$weekend]) $holiday = 1;
                    } else {
                        dd('tidak ada absen di 2 minggu sebelumnya');
                    }
                }

                if (count($attendanceShift) == 1) {
                    if ($today == $weekendEmployeeOfficeHours[end($attendanceShift)]) $saturday = 1;
                }

                if (count($attendanceShift) == 2) {
                    // contoh : employee office hour = [1, 3, 2]
                    // [1,3]
                    $pattern1 = array_slice($employeeOfficeHours, 0, -1);
                    // [3,2]
                    $pattern2 = array_slice($employeeOfficeHours, 1);
                    // [2,1]
                    $pattern3 = [$employeeOfficeHours[2], $employeeOfficeHours[0]];

                    if ($consecutiveHoliday->insertion == 'before') {
                        if ($pattern1 == $attendanceShift) {
                            if ($weekendEmployeeOfficeHours[end($attendanceShift)] == $today) $saturday = 1;

                            if ($today == 'Saturday' || $today == 'Sunday') $holiday = 1;
                        }

                        if ($pattern2 == $attendanceShift) {
                            if ($weekendEmployeeOfficeHours[end($attendanceShift)] == $today) $saturday = 1;

                            if ($today == 'Sunday') $holiday = 1;
                        }

                        if ($pattern3 == $attendanceShift) {
                            if ($weekendEmployeeOfficeHours[end($attendanceShift)] == $today) $saturday = 1;
                        }
                    } else {
                        if ($pattern1 == $attendanceShift) {
                            if ($weekendEmployeeOfficeHours[end($attendanceShift)] == $today) $saturday = 1;

                            if ($today == 'Saturday') $holiday = 1;
                        }

                        if ($pattern2 == $attendanceShift) {
                            if ($today == 'Saturday' || $today == 'Sunday') $holiday = 1;
                        }

                        if ($pattern3 == $attendanceShift) {
                            if ($weekendEmployeeOfficeHours[end($attendanceShift)] == $today) $saturday = 1;
                        }
                    }
                }

                if (count($attendanceShift) == 3) {
                    if ($consecutiveHoliday->insertion == 'before') {
                        if ($attendanceShift == $employeeOfficeHours) {
                            dd($attendanceShift);
                        } else {
                            if ($weekendEmployeeOfficeHours[end($attendanceShift)] == $today) $saturday = 1;
                            if ($today == $listHolidays[$weekendEmployeeOfficeHours[end($attendanceShift)]]) $holiday = 1;
                        }
                    } else {
                        if ($attendanceShift == $employeeOfficeHours) {
                            if ($weekendEmployeeOfficeHours[end($attendanceShift)] == $today) $saturday = 1;
                        } else {
                            if ($weekendEmployeeOfficeHours[end($attendanceShift)] == $today) $saturday = 1;
                        }
                    }
                }
                // dd('weekday');
            } else {
                if ($today == 'Saturday') $saturday = 1;
                if ($today == 'Sunday') $holiday = 1;
            }
        }

        return ['holiday' => $holiday, 'saturday' => $saturday];
    }


    private function getWeekend($date, $attendance)
    {
        $result = 0;
        // nama hari
        $today = date('l', strtotime($date));
        if ($today == 'Friday' || $today == 'Saturday') {
            if ($attendance->employee->payroll_type_id == 1 && $today == 'Saturday') $result = 1;
            if ($attendance->employee->payroll_type_id == 2) {
                # code...
            }
        }
        return $result;
    }

    private function getWeeks($date)
    {
        $cut = substr($date, 0, 8);
        $daylen = 86400;

        $timestamp = strtotime($date);
        $first = strtotime($cut . "00");
        $elapsed = ($timestamp - $first) / $daylen;

        $weeks = 1;

        for ($i = 1; $i <= $elapsed; $i++) {
            $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
            $daytimestamp = strtotime($dayfind);

            $day = strtolower(date("l", $daytimestamp));

            if ($day == 'sunday')  $weeks++;
        }

        return $weeks;
    }
}
