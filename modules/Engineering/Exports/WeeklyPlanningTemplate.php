<?php

namespace Modules\Engineering\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Modules\Production\Models\MachineGroup as Model;

class WeeklyPlanningTemplate implements FromView
{
    public function view(): View
    {
        return view('engineering::weekly-planning.template', [
            'models' => Model::all()
        ]);
    }
}
