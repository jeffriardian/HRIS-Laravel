<?php

namespace Modules\Production\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Production\Models\Machine as Model;

class Machine implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Model::select(['machines.id','machine_groups.name as group','machine_brands.name as brand','machine_types.name as type',
                              'machines.code','machines.year'])
                ->join('machine_groups', 'machines.machine_group_id', '=', 'machine_groups.id')
                ->join('machine_brands', 'machines.machine_brand_id', '=', 'machine_brands.id')
                ->join('machine_types', 'machines.machine_type_id', '=', 'machine_types.id')
                ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Group',
            'Brand',
            'Type',
            'Code',
            'Year',
        ];
    }
}
