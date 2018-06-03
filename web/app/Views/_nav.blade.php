<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/' ) active @endif" href="{{ app('request')->url . '/' }}">Главная</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/debit/' ) active @endif" href="{{ app('request')->url . 'debit/' }}">Доход</a>
    </li>
    <li class="nav-item">
        <a class="nav-link @if( app('request')->path == '/credit/' ) active @endif" href="{{ app('request')->url . 'credit/' }}">Расход</a>
    </li>
</ul>