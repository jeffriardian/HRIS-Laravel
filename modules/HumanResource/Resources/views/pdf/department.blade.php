<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="utf-8"/>
<title>Department</title>
@include('pdf.style')
</head>
<body>
@include('pdf.header')
<table width="100%" class="table-style-one">
    <thead>
    <tr>
        <th width="30px" align=center>#</th>
        <th width="120px">Code</th>
        <th align=left>Name</th>
    </tr>
    </thead>
    <tbody>
    @php
        $i=1;
    @endphp
    @foreach ($models as $model)
    <tr>
        <td align=center>{{ $i++ }}</td>
        <td align=center>{{ $model->code }}</td>
        <td>{{ $model->name }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>