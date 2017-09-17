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
                        <ul class="menu-list" style="background-color: #242424">
                            <li>
                                <a href="{{ route('tickets.index') }}">My tickets</a>
                            </li>
                            <li>
                                <a class="nav-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    Logout
                                </a>
                            </li>
                        </ul>
                    </template>
                </dropdown-menu>
            </div>
    </div>
</nav>