<nav class="menu" style="position: relative">
    <ul class="menu-list">
        @if (auth()->check())
            <li>
                <a href="{{ route('home') }}" class="{{ route_match('home') ? 'is-active' : null }}">
                    <span class="icon">
                        <i class="fa fa-tachometer"></i>
                    </span>

                    <span>
                        Dashboard
                    </span>
                </a>
            </li>

            <li>
                <a href="{{ route('user.edit') }}" class="{{ route_match('user.*') ? 'is-active' : null }}">
                    <span class="icon">
                        <i class="fa fa-user"></i>
                    </span>

                    <span>
                        User details
                    </span>
                </a>
            </li>

            <li>
                <a href="{{ route('tickets.index') }}" class="{{ route_match('tickets.*') ? 'is-active' : null }}">
                    <span class="icon">
                        <i class="fa fa-ticket"></i>
                    </span>

                    <span>
                        Support tickets
                    </span>
                </a>
            </li>

            @can('view', \App\RemoteServer::class)
            <li>
                <a href="{{ route('server.index') }}" class="{{ route_match('server.*') ? 'is-active' : null }}">
                    <span class="icon">
                        <i class="fa fa-server"></i>
                    </span>

                    <span>
                        Server management
                    </span>
                </a>
            </li>
            @endcan

            <li>
                <a href="{{ route('invoice.index') }}" class="{{ route_match('invoice.*') && !route_match('*.create') ? 'is-active' : null }}">
                    <span class="icon">
                        <i class="fa fa-file-text"></i>
                    </span>

                    <span>
                        Billing
                    </span>
                </a>

                @if (route_match('invoice.*') && auth()->user()->can('create', \App\Invoice::class))
                    <ul>
                        <li>
                            <a href="{{ route('invoice.create') }}" class="{{ route_match('invoice.create') ? 'is-active' : null }}">
                                <span class="icon">
                                    <i class="fa fa-plus"></i>
                                </span>

                                <span>Create invoice</span>
                            </a>
                        </li>
                    </ul>
                @endif
            </li>

            @if (auth()->user()->isAdmin())
                <li class="separator"></li>

                <li>
                    <a href="{{ route('clients.index') }}" class="{{ route_match('clients.*') ? 'is-active' : null }}">
                        <span class="icon">
                            <i class="fa fa-users"></i>
                        </span>

                        <span>
                            Billing clients
                        </span>
                    </a>
                </li>
            @endif

            <li class="separator"></li>

            <li>
                <a class="nav-item" href="{{ route('logout') }}"
                   onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                <span class="icon">
                    <i class="fa fa-sign-out"></i>
                </span>

                    Logout
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </li>
        @else
            <li>
                <a href="{{ route('login') }}">
                    <span class="icon">
                        <i class="fa fa-sign-in"></i>
                    </span>

                    <span>Login</span>
                </a>
            </li>
        @endif
    </ul>

</nav>