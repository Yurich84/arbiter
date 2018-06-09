@extends('layout')

@section('content')

    <table class="table table-striped">
        <tr><td>№</td><td>{{ $log->id }}</td></tr>
        <tr><td>Биржа</td><td>{{ $log->broker }}</td></tr>
        <tr><td>Тройка</td><td>{{ $log->trio }}</td></tr>
        <tr><td>Профіт %</td><td>{{ $log->percent }} %</td></tr>
        <tr><td>Мин сумма</td><td>$ {{ $log->min_order }}</td></tr>
        <tr><td>Коментар</td><td>{!! $log->comment !!}</td></tr>
    </table>

    <table class="table table-striped table-dark">
        <thead>
        <td>№</td>
        <td>Пара</td>
        <td>Сума</td>
        <td>Ціна</td>
        <td>Тип</td>
        </thead>

        @foreach($log->orders as $order)
            <tr class="line" data-id="{{ $order->id }}">
                <td>{{ $order->id }}</td>
                <td>{{ $order->pair }}</td>
                <td>{{ $order->among }}</td>
                <td>{{ $order->price }}</td>
                <td>{{ $order->type }}</td>
            </tr>
            <tr id="line_{{ $order->id }}" style="display: none;">
                <td>
                    <h5>Ask (Пропозиція)</h5>
                    <?php $ask_deal = json_decode($order->ask); ?>
                    <table>
                        <tr><td>Ціна:</td><td>{{ $ask_deal[0] }}</td></tr>
                        <tr><td>Кількість:</td><td>{{ $ask_deal[1] }}</td></tr>
                        <tr><td>Результат:</td><td>{{ $ask_deal[2] }}</td></tr>
                    </table>
                </td>
                <td></td><td></td>
                <td>
                    <h5>Bid (Попит)</h5>
                    <?php $bid_deal = json_decode($order->bid); ?>
                    <table>
                        <tr><td>Ціна:</td><td>{{ $bid_deal[0] }}</td></tr>
                        <tr><td>Кількість:</td><td>{{ $bid_deal[1] }}</td></tr>
                        <tr><td>Результат:</td><td>{{ $bid_deal[2] }}</td></tr>
                    </table>
                </td>
            </tr>
        @endforeach
    </table>

@endsection

@section('script')
    <script>
        $(document).ready(function(){
            $(".line").click(function() {
                var id = $(this).data('id');
                var line = $("#line_" + id);
                if(line.is(":visible")){
                    line.hide();
                } else {
                    line.show();
                }
            });
        });
    </script>
@endsection
