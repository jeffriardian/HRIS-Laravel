<?php

namespace Modules\HumanResource\Imports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\HumanResource\Models\BpjsKesehatan;

class BpjsKesehatanImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            BpjsKesehatan::updateOrCreate([
                'card_number' => $row['nokartu'],
                'date' => $row['tglpotong']
            ], [
                'card_number' => $row['nokartu'],
                'employee_salary_deduction' => $row['potonganpegawai'],
                'office_salary_deduction' => $row['potongankantor'],
                'date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tglpotong'])),
                'office' => $row['kantor']
            ]);
        }
    }
}
