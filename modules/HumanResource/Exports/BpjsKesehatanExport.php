<?php

namespace Modules\HumanResource\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\HumanResource\Models\BpjsKesehatan;

class BpjsKesehatanExport implements FromCollection, WithHeadings, ShouldAutoSize
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
        $data = BpjsKesehatan::select('id', 'card_number', 'employee_salary_deduction', 'office_salary_deduction', 'date', 'office')->where('card_number', 'LIKE', '%' . $this->keyword . '%')
            ->where('office', 'LIKE', '%' . $this->office . '%')
            ->whereMonth('date', 'LIKE', '%' . $this->month . '%')
            ->whereYear('date', 'LIKE', '%' . $this->year . '%')
            ->get();
        return $data;
    }

    public function headings(): array
    {
        return [
            '#',
            'No Kartu',
            'Potongan Pegawai',
            'Potongan Kantor',
            'Tanggal Potongan',
            'Kantor',
        ];
    }
}
