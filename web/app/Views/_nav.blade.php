<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/' ) active @endif" href="{{ app('request')->url . '/' }}">Состояние</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/debit/' ) active @endif" href="{{ app('request')->url . 'debit/' }}">Логи</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/credit/' ) active @endif" href="{{ app('request')->url . 'credit/' }}">Подсчет</a>
    </li>
</ul>