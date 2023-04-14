<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="utf-8"/>
<title>Employee</title>
@include('pdf.style')
</head>
<body>
@include('pdf.header')
<table width="100%" class="table-style-one">
    <thead>
    <tr>
        <th width="30px" align=center>#</th>
        <th>No Kartu</th>
        <th >JKK</th>
        <th>JKM</th>
        <th>Pemberi Kerja (TK)</th>
        <th>Tenaga Kerja (TK)</th>
        <th>Pemberi Kerja (JP)</th>
        <th>Tenaga Kerja (JP)</th>
        <th>Tanggal Potong TK</th>
        <th>Tanggal Potong JP</th>
        <th>Bulan</th>
        <th>Tahun</th>
        <th>Kantor</th>
    </tr>
    </thead>
    <tbody>
    @php
        $i=1;
    @endphp
    @foreach ($data as $key)
    <tr>
        <td align=center>{{ $i++ }}</td>
        <td>{{ $key->card_number }}</td>
        <td>{{ $key->jkk }}</td>
        <td>{{ $key->jkm }}</td>
        <td>{{ $key->pk_tk }}</td>
        <td>{{ $key->tk_tk }}</td>
        <td>{{ $key->pk_jp }}</td>
        <td>{{ $key->tk_jp }}</td>
        <td>{{ $key->tk_date }}</td>
        <td>{{ $key->jp_date }}</td>
        <td>{{ $key->month }}</td>
        <td>{{ $key->year }}</td>
        <td>{{ $key->office }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>