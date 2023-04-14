<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HumanResource\Exports\BpjsKesehatanExport;
use Modules\HumanResource\Imports\BpjsKesehatanImport;
use Modules\HumanResource\Models\BpjsKesehatan;

use PDF;
use Spatie\Activitylog\Contracts\Activity;

class BpjsKesehatanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $data = BpjsKesehatan::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        if (request()->keyword != '') {
            $data = $data->where('card_number', 'LIKE', '%' . request()->keyword . '%');
        }
        if (request()->filterOffice != '') {
            $data = $data->where('office', request()->filterOffice);
        }
        if (request()->filterMonth != '') {
            $data = $data->whereMonth('date', request()->filterMonth);
        }
        if (request()->filterYear != '') {
            $data = $data->whereYear('date', request()->filterYear);
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
        $validator = Validator::make(
            [
                'file'      => $request->file,
                'extension' => strtolower($request->file->getClientOriginalExtension()),
            ],
            [
                'file'          => 'required',
                'extension'      => 'required|in:xlsx,xls',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $path = $request->file('file');
        Excel::import(new BpjsKesehatanImport, $path);
        // activity()->useLog('Bpjs Kesehatan')->log('created');
        return response()->json(['status' => 'success'], Response::HTTP_CREATED);
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

    public function exportExcel()
    {
        $keyword = request()->keyword;
        $office = request()->office;
        $month = request()->month;
        $year = request()->year;

        return Excel::download(new BpjsKesehatanExport($keyword, $office, $month, $year), "bpjs_kesehatan_{$keyword}_{$office}_{$month}_{$year}.xlsx");
    }

    public function exportPdf()
    {
        $keyword = request()->keyword;
        $office = request()->office;
        $month = request()->month;
        $year = request()->year;
        $data = BpjsKesehatan::where('card_number', 'LIKE', '%' . $keyword . '%')
            ->where('office', 'LIKE', '%' . $office . '%')
            ->whereMonth('date', 'LIKE', '%' . $month . '%')
            ->whereYear('date', 'LIKE', '%' . $year . '%')
            ->get();
        $pdf = PDF::loadView('humanresource::pdf.bpjs-kesehatan', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}
