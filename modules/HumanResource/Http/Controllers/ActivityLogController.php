<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\HumanResource\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $keyword = request()->keyword;
        $date = request()->date;

        $data = ActivityLog::with('user.employee')->where(function ($query) use ($keyword) {
            $query->whereHas('user.employee', function ($q) use ($keyword) {
                $q->where('name', 'LIKE', '%' . $keyword . '%');
            })
                ->orWhere('log_name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('description', 'LIKE', '%' . $keyword . '%');
        })
            ->latest()
            ->orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));


        if (request()->has('date')) {
            switch (request()->filterByDate) {
                case 'date':
                    $data = $data->whereDate('created_at', $date);
                    break;
                case 'month':
                    $data = $data->whereYear('created_at', Carbon::parse($date)->format('Y'))->whereMonth('created_at', Carbon::parse($date)->format('m'));
                    break;
                case 'year':
                    $data = $data->whereYear('created_at', Carbon::parse($date)->format('Y'));
                    break;
                default:
                    $data = $data;
                    break;
            }
        }

        return new DataCollection($data->paginate(request()->per_page));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('humanresource::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('humanresource::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('humanresource::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
