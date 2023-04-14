<?php

namespace Modules\Engineering\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Engineering\Models\FailureType as Model;

class FailureType implements FromCollection, WithHeadings, ShouldAutoSize
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
