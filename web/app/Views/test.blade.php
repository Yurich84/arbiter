@extends('layout')

@section('content')

    <script>
        var eventSource = new EventSource("/ajax_current");

        eventSource.onmessage = function(e) {
            console.log("Пришло сообщение: " + e.data);
        };
    </script>

@endsection