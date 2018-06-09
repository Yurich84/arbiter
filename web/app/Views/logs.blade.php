@extends('layout')

@section('content')

    <table class="table table-striped table-dark">
        <thead>
        <td>№</td>
        <td>Биржа</td>
        <td>Тройка</td>
        <td>Профіт %</td>
        <td>Мин сумма</td>
        </thead>

        @foreach($logs as $log)
            <tr style="cursor: pointer;" onclick="location.href = '{{ app('request')->url . '/log_show/' . $log->id }}'">
                <td>{{ $log->id }}</td>
                <td>{{ $log->broker }}</td>
                <td>{{ $log->trio }}</td>
                <td>{{ $log->percent }} %</td>
                <td>$ {{ $log->min_order }}</td>
            </tr>
        @endforeach
    </table>

@endsection