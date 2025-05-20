<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            {{-- <li class=mr-auto">
                <a class="navbar-brand" href="{{ route('dashboard') }}" style="padding: 15px;">
                    <span class="brand-logo" style="display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('app-assets/images/logo/logo.png') }}" alt="{{config('app.name')}}"
                            style="max-height: 50px; width: auto; object-fit: contain;" />
                    </span>
                </a>
            </li> --}}
            <li class="nav-link">
                Business Management System
            </li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>

    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class="navigation-header">
                <span>Main</span>
                <i data-feather="more-horizontal"></i>
            </li>

            <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i style="margin-bottom: 5px;" data-feather="home"></i>
                    <span class="menu-title text-truncate">Dashboard</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/sources*') ? 'active' : '' }}">
                <a href="{{ route('sources.index') }}">
                    <i style="margin-bottom: 5px;" data-feather="users"></i>
                    <span class="menu-title text-truncate">Sources</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/clients*') ? 'active' : '' }}">
                <a href="{{ route('clients.index') }}">
                    <i style="margin-bottom: 5px;" data-feather="users"></i>
                    <span class="menu-title text-truncate">Clients</span>
                </a>
            </li>

            <li class="{{ Request::is('admin/projects*') ? 'active' : '' }}">
                <a href="{{ route('projects.index') }}">
                    <i style="margin-bottom: 5px;" data-feather="briefcase"></i>
                    <span class="menu-title text-truncate">Projects</span>
                </a>
            </li>

            <!-- <li class="{{ Request::is('admin/users*') ? 'active' : '' }}">
                <a href="{{ route('users.index') }}">
                    <i style="margin-bottom: 5px;" data-feather="box"></i>
                    <span class="menu-title text-truncate">Users</span>
                </a>
            </li> -->

            <!-- <li class="navigation-header">
                <span>Settings</span>
                <i data-feather="more-horizontal"></i>
            </li>
 
            <li class="{{ Request::is('admin/database/backup*') ? 'active' : '' }}">
                <a href="{{ route('database.backup') }}">
                    <i style="margin-bottom: 5px;" data-feather="download"></i>
                    <span class="menu-title text-truncate">Export Database</span>
                </a>
            </li> -->
            <div class="mb-5"></div>
        </ul>
    </div>
</div>