<nav class="nav is-hidden-tablet">
    <div class="nav-left">
        <h1 style="font-size: 1.7em; padding: .25em .5em; margin-right: .5em; font-weight: 500">
            <a href="/">
                <span style="color: #fff">GEC</span><span style="color: #4EE559">KODE</span>
            </a>
        </h1>
    </div>

    <div class="nav-right has-text-right">
            <div class="nav-toggle is-right" style="margin-right: 0; margin-left: auto">
                <dropdown-menu>
                    <template slot="button" class="nav-toggle is-dark">
                            <span class="icon">
                                <i class="fa fa-bars"></i>
                            </span>
                    </template>

                    <template slot="menu">
                        <ul class="menu-list" style="background-color: #242424; min-width: 230px">
                            @if (auth()->check())
                                <li>
                                    <a href="{{ route('home') }}">
                                        Dashboard
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('user.edit') }}">
                                        User details
                                    </a>
                                </li>

                                <li>
                                    <a href="{{ route('tickets.index') }}">
                                        Support tickets
                                    </a>
                                </li>

                                @can('view', \App\RemoteServer::class)
                                    <li>
                                        <a href="{{ route('server.index') }}">
                                                Server management
                                        </a>
                                    </li>
                                @endcan

                                @can('index', \App\Invoice::class)
                                    <a href="{{ route('invoice.index') }}">
                                        Billing
                                    </a>
                                @endcan

                                @if (auth()->user()->isAdmin())
                                    <li>
                                        <a href="{{ route('clients.index') }}" class="{{ route_match('clients.*') ? 'is-active' : null }}">
                                            Billing clients
                                        </a>
                                    </li>
                                @endif

                                <li class="separator" style="margin: 0 auto;"></li>

                                <li>
                                    <a class="nav-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('login') }}">
                                        Login
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </template>
                </dropdown-menu>
            </div>
    </div>
</nav>