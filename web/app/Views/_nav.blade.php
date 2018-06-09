<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/' ) active @endif" href="{{ app('request')->url . '/' }}">Состояние</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/logs/' ) active @endif" href="{{ app('request')->url . '/logs' }}">Логи</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/credit/' ) active @endif" href="{{ app('request')->url . '/' }}">Подсчет</a>
    </li>
</ul>