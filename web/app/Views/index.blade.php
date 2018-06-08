@extends('layout')

@section('content')

    <h1>Главная</h1>

    <table class="table table-striped table-dark">
        <thead>
            <td>Бижа</td>
            <td>Тройка</td>
            <td>Профіт %</td>
            <td>Мин сумма</td>
        </thead>

        @foreach($currents as $current)
            <tr @if($current->online) style="background: green;" @endif>
                <td>{{ $current->broker }}</td>
                <td>{{ $current->trio }}</td>
                <td>{{ $current->percent }} %</td>
                <td>$ {{ $current->min_order }}</td>
            </tr>
        @endforeach
    </table>

@endsection