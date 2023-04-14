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
        <th >Potongan Pegawai</th>
        <th>Potongan Kantor</th>
        <th>Tanggal Pemotongan</th>
        <th>Kantor</th>
    </thead>
    <tbody>
    @php
        $i=1;
    @endphp
    @foreach ($data as $key)
    <tr>
        <td align=center>{{ $i++ }}</td>
        <td>{{ $key->card_number }}</td>
        <td>{{ $key->employee_salary_deduction }}</td>
        <td>{{ $key->office_salary_deduction }}</td>
        <td>{{ $key->date }}</td>
        <td>{{ $key->office }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>