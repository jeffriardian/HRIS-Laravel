<?php

namespace Modules\HumanResource\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\HumanResource\Models\Country as Model;

class Country implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Model::select(['id','code','name',])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Code',
            'Name'
        ];
    }
}