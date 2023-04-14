<?php

namespace Modules\HumanResource\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\HumanResource\Models\BpjsKetenagakerjaan;

class BpjsKetenagakerjaanExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    private $keyword;
    private $office;
    private $month;
    private $year;

    public function __construct($keyword, $office, $month, $year)
    {
        $this->keyword = $keyword;
        $this->office = $office;
        $this->month = $month;
        $this->year = $year;
    }

    public function collection()
    {
        $data = BpjsKetenagakerjaan::select([
            'id',
            'card_number',
            'jkk',
            'jkm',
            'pk_tk',
            'tk_tk',
            'pk_jp',
            'tk_jp',
            'tk_date',
            'jp_date',
            'month',
            'year',
            'office',
        ])
            ->where('card_number', 'LIKE', '%' . $this->keyword . '%')
            ->where('office', 'LIKE', '%' . $this->office . '%')
            ->where('month', 'LIKE', '%' . $this->month . '%')
            ->where('year', 'LIKE', '%' . $this->year . '%')
            ->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            '#',
            'No Kartu',
            'JKK',
            'JKM',
            'Pemberi Kerja (TK)',
            'Tenaga Kerja (TK)',
            'Pemberi Kerja (JP)',
            'Tenaga Kerja (JP)',
            'Tanggal Potong TK',
            'Tanggal Potong JP',
            'Bulan',
            'Tahun',
            'Kantor',
        ];
    }
}
