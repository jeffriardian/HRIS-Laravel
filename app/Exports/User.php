<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\User as Model;

class User implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        return Model::select(['id','name',])->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name'
        ];
    }
}
