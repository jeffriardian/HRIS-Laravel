<?php

namespace Modules\HumanResource\Http\Controllers;

use App\Http\Resources\DataCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Modules\HumanResource\Exports\BpjsKetenagakerjaanExport;
use Modules\HumanResource\Imports\BpjsKetenagakerjaanImport;
use Modules\HumanResource\Models\BpjsKetenagakerjaan;

use PDF;

class BpjsKetenagakerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // dd(request()->filter);
        $data = BpjsKetenagakerjaan::orderBy(request()->order_column, (request()->order_direction == 'true' ? 'DESC' : 'ASC'));
        if (request()->keyword != '') {
            $data = $data->where('card_number', 'LIKE', '%' . request()->keyword . '%');
        }
        if (request()->filterOffice != '') {
            $data = $data->where('office', request()->filterOffice);
        }
        if (request()->filterMonth != '') {
            $data = $data->where('month', request()->filterMonth);
        }
        if (request()->filterYear != '') {
            $data = $data->where('year', request()->filterYear);
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
                'file' => 'required',
                'extension' => 'required|in:xlsx,xls',
            ]
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        // dd($request->all());
        $path = $request->file('file');
        Excel::import(new BpjsKetenagakerjaanImport, $path);
        // activity()->useLog('Bpjs Ketenagakerjaan')->log('created');
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
        // $now = now();
        $keyword = request()->keyword;
        $office = request()->office;
        $month = request()->month;
        $year = request()->year;
        return Excel::download(new BpjsKetenagakerjaanExport($keyword, $office, $month, $year), "bpjs_ketenagakerjaan_{$keyword}_{$office}_{$month}_{$year}.xlsx");
    }

    public function exportPdf()
    {
        $keyword = request()->keyword;
        $office = request()->office;
        $month = request()->month;
        $year = request()->year;
        $data = BpjsKetenagakerjaan::where('card_number', 'LIKE', '%' . $keyword . '%')
            ->where('office', 'LIKE', '%' . $office . '%')
            ->where('month', 'LIKE', '%' . $month . '%')
            ->where('year', 'LIKE', '%' . $year . '%')
            ->get();
        $pdf = PDF::loadView('humanresource::pdf.bpjs-ketenagakerjaan', compact('data'))->setPaper('a4', 'landscape');
        return $pdf->stream();
    }
}
