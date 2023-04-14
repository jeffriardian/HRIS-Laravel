<!doctype html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;" charset="utf-8"/>
<title>Religion</title>
<style type="text/css">
    body {
        font-family: verdana, arial, sans-serif;
    }
    
    .header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #AAAAAA;
        background-color: #000034;
        color: white;
    }
    .img {
        margin-left: 20px;
    }
    .txt {
        margin-right: 20px;
        font-family: verdana, arial, sans-serif;
    }

    table.table-style-one {
        font-size: 11px;
        border: 1px solid grey;
        border-collapse: collapse;
    }

    table.table-style-one th {
        padding: 8px;
        border: 1px solid grey;
        background-color: #B3B3B3;
    }

    table.table-style-one td {
        padding: 8px;
        border: 1px solid grey;
        background-color: #ffffff;
    }
</style>
</head>
<body>

<table width="100%" class="header">
    <tr>
        <td valign="middle"><img src="{{ public_path('/assets/images/logo-light.png') }}" class="img" alt="" width="150"/></td>
        <td align="right">
            <h3 class="txt">PT Sarana Makin Mulya</h3>
            <pre class="txt">
                Jl. Raya Cimareme No.273
                Kab. Bandung Barat
                Jawa Barat
                (0268) 66040
            </pre>
        </td>
    </tr>
</table>

<table width="100%" class="table-style-one">
    <thead>
    <tr>
        <th width="30px" align=center>#</th>
        <th align=left>Name</th>
        <th align=left>Worship Place</th>
    </tr>
    </thead>
    <tbody>
    @php
        $i=1;
    @endphp
    @foreach ($models as $model)
    <tr>
        <td align=center>{{ $i++ }}</td>
        <td>{{ $model->name }}</td>
        <td>{{ $model->worship_place }}</td>
    </tr>
    @endforeach
    </tbody>
</table>

</body>
</html>