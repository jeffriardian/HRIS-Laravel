<table>
    <thead>
        <tr>
            <th align="center"><b>#</b></th>
            <th align="center"><b>Code</b></th>
            <th><b>Name</b></th>
            @for ($i = 1; $i <= 7; $i++)
                <th align="center"><b>{{ $i }}</b></th>
            @endfor
        </tr>
    </thead>
    <tbody>
        @foreach($models as $index => $model)
        <tr>
            <td align="center">{{ $index+1 }}</td>
            <td align="center">{{ $model->code }}</td>
            <td>{{ $model->name }}</td>
            @for ($i = 1; $i <= 7; $i++)
                <td align="center"></td>
            @endfor
        </tr>
        @endforeach
    </tbody>
</table>