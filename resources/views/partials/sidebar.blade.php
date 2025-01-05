<aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
    <div class="sidebar-brand">
        <a href="{{ route('dashboard') }}" class="brand-link">
            <img src="{{ asset('wawawa.jpeg') }}" alt="AdminLTE Logo"
                class="brand-image opacity-75 rounded shadow">
            <span class="brand-text fw-light">WAWAWA</span>
        </a>
    </div>

    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
                {{-- Dashboard - Visible to all roles --}}
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-speedometer2"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                {{-- Finance Manager Menu Items --}}
                @if(auth()->user()->role === 'finance')
                    <li class="nav-item">
                        <a href="{{ route('transactions.index') }}"
                            class="nav-link {{ request()->routeIs('transactions.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-currency-exchange"></i>
                            <p>Transactions</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('journal-entries.index') }}"
                            class="nav-link {{ request()->routeIs('journal-entries.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-journal-text"></i>
                            <p>Journal Entries</p>
                        </a>
                    </li>
                @endif

                {{-- Owner Menu Items --}}
                @if(auth()->user()->role === 'owner')
                    <li class="nav-item">
                        <a href="{{ route('reports.income-statement') }}"
                            class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-file-earmark-bar-graph"></i>
                            <p>Reports</p>
                        </a>
                    </li>
                @endif

                {{-- Admin Menu Items --}}
                @if(auth()->user()->role === 'admin')
                    <li class="nav-item">
                        <a href="{{ route('accounts.index') }}"
                            class="nav-link {{ request()->routeIs('accounts.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-wallet2"></i>
                            <p>Accounts</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('categories.index') }}"
                            class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-bookmark"></i>
                            <p>Categories</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}"
                            class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <i class="nav-icon bi bi-people"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
