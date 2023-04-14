<?php

namespace Modules\HumanResource\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\HumanResource\Models\BpjsKetenagakerjaan;

class BpjsKetenagakerjaanImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            BpjsKetenagakerjaan::updateOrCreate([
                'card_number' => $row['no_kartu'],
                'month' => $row['bulan'],
                'year' => $row['tahun'],
                'office' => $row['kantor']
            ], [
                'card_number' => $row['no_kartu'],
                'jkk' => $row['jkk'],
                'jkm' => $row['jkm'],
                'pk_tk' => $row['pemberi_kerja_tk'],
                'tk_tk' => $row['tenaga_kerja_tk'],
                'pk_jp' => $row['pemberi_kerja_jp'],
                'tk_jp' => $row['tenaga_kerja_jp'],
                'tk_date' => $row['tglpotong_tk'],
                'jp_date' => $row['tglpotong_jp'],
                'month' => $row['bulan'],
                'year' => $row['tahun'],
                'office' => $row['kantor'],
            ]);
        }
    }
}
