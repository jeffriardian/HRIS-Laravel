<?php

namespace Modules\HumanResource\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\HumanResource\Models\Employee as Model;

class Employee implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Model::select([
            'employees.id', 'departments.name as department',
            'employees.nik', 'employees.ktp', 'employees.name', 'employees.is_active',
        ])
            ->join('departments', 'employees.department_id', '=', 'departments.id')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Department',
            'NIK',
            'KTP',
            'Name',
            'Is Active'
        ];
    }
}
