@can('atk.view')
    <div class="sidebar-group">Inventaris</div>

    <a href="{{ route('atk.items.index') }}" class="{{ request()->routeIs('atk.items.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor">
            <path d="M3 3h2v18H3V3zm6 6h2v12h-2V9zm6-4h2v16h-2V5z"/>
        </svg>
        Data ATK
    </a>

    @can('atk.edit')
    <a href="{{ route('atk.stock-in.index') }}" class="{{ request()->routeIs('atk.stock-in.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Barang Masuk
    </a>

    <a href="{{ route('atk.stock-out.index') }}" class="{{ request()->routeIs('atk.stock-out.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M19 13H5v-2h14v2z"/></svg>
        Barang Keluar
    </a>
    @endcan

    <a href="{{ route('atk.requests.index') }}" class="{{ request()->routeIs('atk.requests.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M14 2H6c-1.1 0-1.99.9-1.99 2L4 20c0 1.1.89 2 1.99 2H18c1.1 0 2-.9 2-2V8l-6-6zm2 16H8v-2h8v2zm0-4H8v-2h8v2zm-3-5V3.5L18.5 9H13z"/></svg>
        Permintaan ATK
    </a>
@endcan
