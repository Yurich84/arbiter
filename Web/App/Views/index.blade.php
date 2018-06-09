@extends('layout')

@section('content')

    <table class="table table-striped table-dark">
        <thead>
            <td>Биржа</td>
            <td>Тройка</td>
            <td>Профіт %</td>
            <td>Мин сумма</td>
            <td>Максимум %</td>
            <td>Онлайн</td>
        </thead>

        @foreach($currents as $current)
            <tr @if($current->online) style="color: lightgreen" @endif >
                <td>{{ $current->broker }}</td>
                <td>{{ $current->trio }}</td>
                <td>{{ $current->percent }} %</td>
                <td>$ {{ $current->min_order }}</td>
                <td>{{ $current->max_percent }} %</td>
                <td align="center"><i class="fas fa-circle"
                     style="color: @if($current->online) lightgreen @else red @endif ;"
                ></i></td>
            </tr>
        @endforeach
    </table>

@endsection