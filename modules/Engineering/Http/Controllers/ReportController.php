<?php

namespace Modules\Engineering\Http\Controllers;

use App\Http\Resources\DataCollection;
use App\Http\Controllers\Controller;

use Modules\Production\Models\Machine;
use Modules\HumanResource\Models\Employee;
use Modules\Engineering\Models\WorkOrder;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function physicalAvailability()
    {
        $data = Machine::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'))
                ->with(['machineGroup','machineBrand','machineType','jobCards'=> function ($query) {
                    $query->whereHas('workOrder', function ($query) {
                        $query->whereIn('work_order_action_id', [2,3]);
                    });
                }]);
        if (request()->keyword != '') {
            $data = $data->where('area', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mtbf()
    {
        $data = Machine::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'))
                ->with(['machineGroup','machineBrand','machineType','jobCards'=> function ($query) {
                    $query->whereHas('workOrder', function ($query) {
                        $query->whereIn('work_order_action_id', [2,3]);
                    });
                }]);
        if (request()->keyword != '') {
            $data = $data->where('area', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mttr()
    {
        $data = Machine::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'))
                ->with(['machineGroup','machineBrand','machineType','jobCards'=> function ($query) {
                    $query->whereHas('workOrder', function ($query) {
                        $query->whereIn('work_order_action_id', [2,3]);
                    });
                }]);
        if (request()->keyword != '') {
            $data = $data->where('area', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function rmc()
    {
        return new DataCollection([]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function effectiveHour()
    {
        $data = Employee::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'))
                ->with(['improvements']);
        if (request()->keyword != '') {
            $data = $data->where('name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function workOrder()
    {
        $data = WorkOrder::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'))
                ->with(['workOrderAction']);
        if (request()->keyword != '') {
            $data = $data->where('code', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function involvement()
    {
        $data = Employee::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'))
                ->with(['improvements']);
        if (request()->keyword != '') {
            $data = $data->where('name', 'LIKE', '%' . request()->keyword . '%');
        }
        return new DataCollection($data->paginate(request()->per_page));
    }
}
