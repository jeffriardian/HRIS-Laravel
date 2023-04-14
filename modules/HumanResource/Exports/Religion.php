<?php

namespace Modules\HumanResource\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\HumanResource\Models\Religion as Model;

class Religion implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Model::select(['id','name','worship_place',])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Worship Place'
        ];
    }
}
