@php
$dropdownActive = false;
foreach($item->getChildren() as $subItem) {

    if (!empty($subItem->getExtra('routes'))) {
        if (in_array(Route::currentRouteName(), $subItem->getExtra('routes'))) {
            $dropdownActive = true;
        }
    }

    if ($subItem->getAttribute('route') == Route::currentRouteName()) {
        $dropdownActive = true;
    }
    if (request()->getUri() == $subItem->getUri()) {
        $dropdownActive = true;
    }
}
@endphp

<li class="nav-item dropdown">
    <a href="@if (!empty($subItem->getAttribute('route'))) {{route($subItem->getAttribute('route'))}} @else {{ $subItem->getUri() }} @endif" class="nav-link fs-3 dropdown-toggle" @if($dropdownActive) x-init="setTimeout(function() { $el.classList.add('active'); }, 300);" @endif data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="true">
        {!! $item->getAttribute('icon') !!}
        <div x-init="setTimeout(function() { $el.classList.remove('placeholder'); $el.classList.remove('placeholder-xs'); }, 300);" class="placeholder placeholder-xs">
            <span class="badge-holder">
                {{_e($item->getName())}}
            </span>
        </div>
    </a>
    <div class="dropdown-menu" @if($dropdownActive) x-init="setTimeout(function() { $el.classList.add('show'); }, 300);" @endif data-bs-popper="static">
        <div class="dropdown-menu-columns">
            <div class="dropdown-menu-column">
                @foreach($item->getChildren() as $subItem)
                    <div class="dropdown-menu-column-item">
                    @php
                    $subItemIsActive = false;
                    if ($subItem->getAttribute('route') == Route::currentRouteName()) {
                        $subItemIsActive = true;
                    }
                    if (!empty($subItem->getExtra('routes'))) {
                        if (in_array(Route::currentRouteName(), $subItem->getExtra('routes'))) {
                            $subItemIsActive = true;
                        }
                    }
                    @endphp

                    <a href="@if (!empty($subItem->getAttribute('route'))) {{route($subItem->getAttribute('route'))}} @else {{ $subItem->getUri() }} @endif" class="dropdown-item justify-content-between @if($subItemIsActive) active @endif">
                       <span>
                            {{_e($subItem->getName())}}
                       </span>
                        <span data-href="" class="add-new" data-bs-toggle="tooltip" title="">
                         <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M240 656q-33 0-56.5-23.5T160 576q0-33 23.5-56.5T240 496q33 0 56.5 23.5T320 576q0 33-23.5 56.5T240 656Zm240 0q-33 0-56.5-23.5T400 576q0-33 23.5-56.5T480 496q33 0 56.5 23.5T560 576q0 33-23.5 56.5T480 656Zm240 0q-33 0-56.5-23.5T640 576q0-33 23.5-56.5T720 496q33 0 56.5 23.5T800 576q0 33-23.5 56.5T720 656Z"/></svg>
                        </span>
                    </a>
                    <div class="mw-admin-sidebar-navigation-menu">
                        <div class="card card-sm">
                            <div class="card-body">
                                <nav class="nav flex-column">
                                    <a class="  mw-admin-action-links btn btn-link text-decoration-none active" aria-current="page" href="#">Active Active Active</a>
                                    <a class=" mw-admin-action-links btn btn-link text-decoration-none " href="#">Link Link</a>
                                    <a class=" mw-admin-action-links btn btn-link text-decoration-none " href="#">Link</a>
                                    <a class="  mw-admin-action-links btn btn-link text-decoration-none disabled">Disabled Disabled</a>
                                </nav>
                            </div>
                        </div>
                    </div>
                    </div>
                @endforeach 
            </div>
        </div>
    </div>
</li>
