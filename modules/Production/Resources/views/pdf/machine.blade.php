<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="utf-8"/>
<title>Machine</title>
@include('pdf.style')
</head>
<body>
@include('pdf.header')
<table width="100%" class="table-style-one">
    <thead>
    <tr>
        <th width="30px" align=center>#</th>
        <th>Group</th>
        <th>Brand</th>
        <th>Type</th>
        <th width="120px">Code</th>
        <th width="120px">Year</th>
    </tr>
    </thead>
    <tbody>
    @php
        $i=1;
    @endphp
    @foreach ($models as $model)
    <tr>
        <td align=center>{{ $i++ }}</td>
        <td>{{ $model->machineGroup ? $model->machineGroup->name : '' }}</td>
        <td>{{ $model->machineBrand ? $model->machineBrand->name : '' }}</td>
        <td>{{ $model->machineType ? $model->machineType->name : ''}}</td>
        <td>{{ $model->code }}</td>
        <td>{{ $model->year }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>