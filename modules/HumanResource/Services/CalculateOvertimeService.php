<?php

namespace Modules\HumanResource\Services;

use DateTime;
use Modules\HumanResource\Models\DummyAttendance;
use Modules\HumanResource\Models\Employee;
use Modules\HumanResource\Models\OvertimeRates;

class CalculateOvertimeService
{
    private $basicSalary;
    private $overtime;

    public function __construct($basicSalary, $overtime)
    {
        $this->basicSalary = $basicSalary;
        $this->overtime = $overtime;
    }

    public function calculateHourlyOvertimePayBySPL()
    {
        $employee = Employee::whereId($this->overtime->employee_id)->firstOrFail();

        $resultCompareWithAttendance = $this->compareWithAttendanceAndCalculateRealOvertimeHours($employee->nik, $employee->payroll_type_id);
        $overtime = abs($resultCompareWithAttendance['overtime']);
        $letness = $resultCompareWithAttendance['letness'];
        $autoOvertime = $resultCompareWithAttendance['autoOvertime'];
        $break = $resultCompareWithAttendance['break'];
        $autoOvertimeWithoutSPL = 0;
        $L1 = $L2 = $L3 = 0;
        $ratesL1 = $ratesL2 = $ratesL3 = 0;

        dd($overtime);

        if ((bool) $this->overtime->without_reducing_rest_hours) $autoOvertime = 0;

        // jika tipe gaji karyawan adalah overtime
        if ($employee->salary_type_id == 2) {
            // jika lembur di tanggal merah / di hari liburnya karyawan
            if ((bool) $this->overtime->is_holiday) {
                $overtimeMinusBreak = $this->convertTotalOvertimeHours($overtime) - $break;
                $totalOvertime = $overtimeMinusBreak + $this->convertTotalOvertimeHours($autoOvertime);
                $defaultL2Hours = 8; // 8 jam
                $defaultL3Hours = 0.5; // 30 menit

                if ($totalOvertime < $defaultL2Hours) {
                    $L2 = $totalOvertime;
                    if ($employee->payroll_type_id == 2) {
                        $ratesL2 = round($this->getBasicL2Hours() * $L2);
                    }
                } else {
                    $multiple = floor($totalOvertime / $defaultL2Hours);
                    $L3 = $defaultL3Hours * $multiple;
                    $L2 = ($totalOvertime - $L3) - $break;
                    if ($employee->payroll_type_id == 2) {
                        $ratesL2 = round($this->getBasicL2Hours() * $L2);
                        $ratesL3 = round($this->getBasicL3Hours() * $L3);
                    }
                }
            } else {
                // jika employee dengan gaji mingguan
                if ($employee->payroll_type_id == 2) {
                    $overtimeMinusBreak = $this->convertTotalOvertimeHours($overtime) - $break;
                    $totalOvertime = $overtimeMinusBreak + $this->convertTotalOvertimeHours($autoOvertime);
                    // jika operator OSI
                    if ($employee->company_id != 1 && $employee->company_id != 2) {
                        $L1 = $totalOvertime + $autoOvertimeWithoutSPL;
                        $ratesL1 = round($this->getBasicL1Hours() * $L1);
                    } else {
                        if ($totalOvertime > 1) {
                            $L1 = 1;
                            $L2 = $totalOvertime - $L1;
                            $ratesL1 = round($this->getBasicL1Hours() * $L1);
                            $ratesL2 = round($this->getBasicL2Hours() * $L2);
                        } else {
                            $L1 = $totalOvertime;
                            $ratesL1 = round($this->getBasicL1Hours() * $L1);
                        }
                    }
                } else {
                    // 
                }
            }
        }
        dd("L1 = $L1, L2 = $L2, L3 = $L3, Break = $break");
        dd("Overtime = $overtime", "Letness = $letness", "Auto lembur = $autoOvertime", "Total istirahat = $break");
    }

    private function compareWithAttendanceAndCalculateRealOvertimeHours($nik, $payroll)
    {
        $attendance = DummyAttendance::with('shift.details')->where('employee_code', $nik)->whereDate('scan_in', $this->overtime->request_date)->firstOrFail();

        $scanIn = new DateTime($attendance->scan_in); // get scan in karyawan dari absensi
        $scanOut = new DateTime($attendance->scan_out); // get scan out karyawan dari absensi

        $intervalScan = (float)$scanOut->diff($scanIn)->format('%H.%i'); // get interval absensi dari scan in sampa scan out

        $letness = 0; // default keterlambatan 0
        $totalOvertime = $this->overtime->total_time; // default total overtime

        // jika tidak dipotong dengan jam istirahat
        if ((bool) $this->overtime->without_reducing_rest_hours) {
            // menghitung real total overtime berdasarkan interval scan in sampai scan out
            $intervalScan = (float)$scanOut->diff($scanIn)->format('%H.%i');

            /*  
                jika interval scan in sampai scan out kurang dari pengajuan lembur
                maka total lembur = interval scan
            */
            if ($intervalScan < $this->overtime->total_time) $totalOvertime = $intervalScan;
        } else {
            // mendapatkan jam wajib kerja dan jadwal jam masuk
            if ((bool)$this->overtime->is_holiday) { // jika di hari libur karyawan atau di tanggal merah
                $mandatoryWorkingHours = 0; // wajib kerja
                $scheduleIn = 0;
                // jika tidak ada jam awal
                if (is_null($this->overtime->start_time_holiday)) {
                    $scheduleIn = $attendance->scan_in;
                } else {
                    $startTime = new DateTime($this->overtime->start_time_holiday);
                    $endTime = new DateTime($this->overtime->end_time_holiday);
                    $mandatoryWorkingHours = (float) $endTime->diff($startTime)->format('%H.%i');
                    $scheduleIn = (new DateTime($this->overtime->start_time_holiday))->format('H:i:s');
                }
            } else { // jika bukan di tanggal merah
                if ($payroll == 1) {
                    $mandatoryWorkingHours = 6.10;
                } else {
                    if ((bool) $this->overtime->is_saturday) {
                        $mandatoryWorkingHours = 5;
                    } else {
                        $mandatoryWorkingHours = 7;
                    }
                }
                if ((bool) $this->overtime->is_saturday) {
                    $scheduleIn = $attendance->shift->details->weekend_in; // jadwal employee pada jam masuk
                } else {
                    $scheduleIn = $attendance->shift->details->weekday_in; // jadwal employee pada jam masuk
                }
            }

            // get auto dapat lembur
            $autoOvertime = $this->getDefaultAutoOvertime((bool)$this->overtime->is_holiday, $payroll);

            // menghitung keterlambatan
            if (is_null($this->overtime->start_time_holiday) && (bool)$this->overtime->is_holiday) {
                $letness = 0;
            } else {
                // (scan in karyawan - jadwal employee pada jam masuk) (return detik) / 60 detik = return menit
                $letness = (strtotime($scanIn->format('H:i:s')) - strtotime($scheduleIn)) / 60;
            }
            // check auto overtime
            if (!(bool) $this->overtime->is_holiday) {
                if ($intervalScan > $mandatoryWorkingHours && $intervalScan < 8) {
                    $mandatoryWorkingHoursPlusBreak = number_format($mandatoryWorkingHours + 0.30, 2);
                    if ($payroll == 1) {
                        if (is_null($this->overtime->overtime_before_duration)) {
                            if ($letness < 0) {
                                if (abs($letness) > 59) {
                                    $sisa = abs($letness) % 60;
                                    $hasil = (abs($letness) - $sisa) / 60;
                                } else {
                                    $hasil = "0." . abs($letness);
                                }

                                $interval = number_format($intervalScan - $hasil, 2);
                                if ($interval <= $mandatoryWorkingHoursPlusBreak) {
                                    $autoOvertime = 0;
                                } else {
                                    $splitInterval = explode('.', $interval);
                                    if ($splitInterval[0] > 6) {
                                        $result = 20 + $splitInterval[1];
                                    } else {
                                        $result = $splitInterval[1] - 40;
                                    }
                                    $autoOvertime = "0.$result";
                                }
                            } else {
                                if ($intervalScan <= $mandatoryWorkingHoursPlusBreak) {
                                    $autoOvertime = 0;
                                } else {
                                    $splitInterval = explode(".", $intervalScan);
                                    $splitMandatory = explode(".", $mandatoryWorkingHoursPlusBreak);
                                    if ($splitInterval[0] == $splitMandatory[0]) {
                                        $result = $splitInterval[1] - $splitMandatory[1];
                                        if ($result < 10) {
                                            $result = "0.0$result";
                                        } else {
                                            $result = "0.$result";
                                        }
                                        $autoOvertime = $result;
                                    } else {
                                        if (count($splitInterval) > 1) {
                                            $result = 20 + $splitInterval[1];
                                            if ($result > 20 && $result < 60) {
                                                $result = "0.$result";
                                            } else {
                                                $result = $result / 60;
                                                if ($result > 1.20) $result = 1.20;
                                            }
                                        } else {
                                            $result = number_format(0.20, 2);
                                        }
                                        $autoOvertime = $result;
                                    }
                                }
                            }
                        }
                    } else {
                        if ((bool) $this->overtime->is_saturday && is_null($this->overtime->overtime_before_duration)) {
                            if ($letness < 0) {
                                if (abs($letness) > 59) {
                                    $sisa = abs($letness) % 60;
                                    $hasil = (abs($letness) - $sisa) / 60;
                                } else {
                                    $hasil = "0." . abs($letness);
                                }

                                $interval = number_format($intervalScan - $hasil, 2);
                                if ($interval <= $mandatoryWorkingHoursPlusBreak) {
                                    $autoOvertime = 0;
                                } else {
                                    $a = number_format($interval - $mandatoryWorkingHoursPlusBreak, 2) * 60;
                                    $b = floor(floor($a) / 30);
                                    $result = 0;
                                    for ($i = 0; $i < $b; $i++) {
                                        $result += 0.5;
                                    }
                                    $c = explode('.', $result);
                                    if (count($c) > 1) $result = "$c[0].30";
                                    $autoOvertime = $result;
                                }
                            } else {
                                if ($intervalScan <= $mandatoryWorkingHoursPlusBreak) {
                                    $autoOvertime = 0;
                                } else {
                                    $a = number_format($intervalScan - $mandatoryWorkingHoursPlusBreak, 2) * 60;
                                    $b = floor(floor($a) / 30);
                                    $result = 0;
                                    for ($i = 0; $i < $b; $i++) {
                                        $result += 0.5;
                                    }
                                    $c = explode('.', $result);
                                    if (count($c) > 1) $result = "$c[0].30";
                                    $autoOvertime = $result;
                                }
                            }
                        } else {
                            $autoOvertime = 0;
                        }
                    }
                } else if ($intervalScan < $mandatoryWorkingHours) {
                    $autoOvertime = 0;
                }
            }

            if (abs($letness) > 59) {
                $sisa = abs($letness) % 60;
                $hasil = (abs($letness) - $sisa) / 60;
            } else {
                $hasil = "0." . abs($letness);
            }

            // get total jam lembur
            // jika interval scan lebih kecil dari jam wajib kerja (6.10 / 5 / 7)
            if ($intervalScan < $mandatoryWorkingHours) {
                $totalOvertime = 0; // tidak mendapatkan kelebihan lembur dan lembur auto
            } else {  // jika interval scan lebih besar dari jam wajib kerja
                // jika di hari libur
                if ((bool)$this->overtime->is_holiday) {
                    if ($intervalScan < $totalOvertime) $totalOvertime = $intervalScan;
                } else {
                    if ($intervalScan > $mandatoryWorkingHours && $intervalScan <= 8) {
                        // $totalOvertime = $autoOvertime;
                        $totalOvertime = 0;
                    } elseif ($intervalScan > 8) {
                        if ($letness < 0) {
                            $totalWorkHoursMinusOvertime = $intervalScan - $hasil - number_format($this->overtime->total_time, 2);
                            if ($totalWorkHoursMinusOvertime < 8) {
                                $totalOvertime = number_format($this->overtime->total_time - (8 - $totalWorkHoursMinusOvertime), 2);
                            }
                        } else {
                            $totalWorkHoursMinusOvertime = ($intervalScan - number_format($this->overtime->total_time, 2));
                            if ($totalWorkHoursMinusOvertime < 8) {
                                $totalOvertime = number_format($this->overtime->total_time - (8 - $totalWorkHoursMinusOvertime), 2);
                            }
                        }
                    }
                }
            }
            $totalBreak = $this->getTotalBreak($totalOvertime);
        }

        return ['overtime' => number_format($totalOvertime, 2), 'letness' => $letness, 'autoOvertime' => number_format($autoOvertime, 2), 'break' => $totalBreak];
    }

    private function checkAutoOvertime()
    {
        // 
    }

    private function getDefaultAutoOvertime($holiday, $payroll)
    {
        if ($holiday) {
            return 0;
        } else {
            if ($payroll == 1) {
                // return 1.3;
                return 1.20;
            } else {
                if ((bool) $this->overtime->is_saturday) {
                    // return 2.5;
                    return 2.30;
                } else {
                    // return 0.5;
                    return 0.30;
                }
            }
        }
    }

    private function getTotalLetness($letness)
    {
        $multiple = floor($letness / 30);
        $result = 0;
        for ($i = 0; $i <= $multiple; $i++) {
            $result += 0.5;
        }
        return $result;
    }

    private function getTotalBreak($totalOvertimeHours)
    {
        $totalTime = $totalOvertimeHours;
        $totalBreak = 0;
        $break = 5;

        $multiple = floor($totalTime / 8); // jumlah kelipatan dari total overtime

        for ($i = 0; $i <= $multiple; $i++) {
            if ($totalTime >= $break) {
                $totalBreak += 0.5;
            }
            $break += 7;
        }
        return $totalBreak;
    }

    private function convertTotalOvertimeHours($time)
    {
        $split = explode('.', $time);
        $hours = $split[0];
        $result = 0;
        if (count($split) > 1) {
            if ($split[1] > 0) {
                $result = "$hours.5";
            } else {
                $result = $hours;
            }
        } else {
            $result = $hours;
        }
        // $result = count($split) > 1 ?  $hours . '.5' : $hours;

        return (float)$result;
    }

    private function totalOvertime($time)
    {
        $split = explode(".", $time);
        $hours = (int) $split[0];
        $minutes = 0;
        if (count($split) > 1) {
            if (strlen($split[1] == 1)) {
                $minutes = (int)"$split[1].0";
            } else {
                $minutes = (int)$split[1];
            }
        }

        if ($minutes < 30) {
            if ($minutes < 25) $minutes = 0;

            if ($minutes >= 25 && $minutes <= 30) $minutes = 30;
        } else {
            if ($minutes < 55 && $minutes > 30) {
                // if ($hours > 0) $hours--;
                $minutes = 30;
            }

            if ($minutes >= 55 && $minutes <= 59) {
                $hours++;
                $minutes = 0;
            }
        }
        // dd($hours, $minutes);
        return (float)number_format("$hours.$minutes", 2);
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
}
