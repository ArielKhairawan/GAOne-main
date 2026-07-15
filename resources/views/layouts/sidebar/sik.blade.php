@canany(['sik.create', 'sik.scan', 'sik.view'])
    <div class="sidebar-group">Surat Izin Keluar</div>

    @can('sik.create')
    <a href="{{ route('sik.create') }}" class="{{ request()->routeIs('sik.create') || request()->routeIs('sik.store') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M13 11h6v2h-6v6h-2v-6H5v-2h6V5h2v6z"/></svg>
        Pengajuan SIK
    </a>
    @endcan

    @can('sik.scan')
    <a href="{{ route('sik.security.dashboard') }}" class="{{ request()->routeIs('sik.security.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h8v8H3V3zm10 0h8v8h-8V3zM3 13h8v8H3v-8zm10 10h8v-8h-8v8z"/></svg>
        Scan Security
    </a>
    @endcan

    @can('sik.view')
    <a href="{{ route('sik.index') }}" class="{{ request()->routeIs('sik.index') || request()->routeIs('sik.show') || request()->routeIs('sik.edit') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
        Riwayat SIK
    </a>
    @endcan
@endcanany
