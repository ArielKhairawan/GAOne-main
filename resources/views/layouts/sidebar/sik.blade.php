@canany(['sik.view', 'sik.create', 'sik.approve', 'sik.scan'])
    <div class="sidebar-group">Surat Izin Keluar</div>

    @can('sik.view')
    <a href="{{ route('sik.dashboard') }}" class="{{ request()->routeIs('sik.dashboard') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
        Dashboard SIK
    </a>
    @endcan

    @can('sik.create')
    <a href="{{ route('sik.create') }}" class="{{ request()->routeIs('sik.create') || request()->routeIs('sik.store') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
        Pengajuan SIK
    </a>
    @endcan

    @can('sik.approve')
    <a href="{{ route('sik.approvals.index') }}" class="{{ request()->routeIs('sik.approvals.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
        Approval SIK
    </a>
    @endcan

    @can('sik.scan')
    <a href="{{ route('sik.security.dashboard') }}" class="{{ request()->routeIs('sik.security.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h6v2H6v4H4V4zm10 0h6v6h-2V6h-4V4zM4 14h2v4h4v2H4v-6zm16 0h-2v4h-4v2h6v-6zM9 9h6v6H9V9z"/></svg>
        Scan Security
    </a>
    @endcan

    @can('sik.view')
    <a href="{{ route('sik.index') }}" class="{{ request()->routeIs('sik.index') || request()->routeIs('sik.show') || request()->routeIs('sik.edit') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
        Riwayat SIK
    </a>
    @endcan

    @can('sik.export')
    <a href="{{ route('sik.laporan.index') }}" class="{{ request()->routeIs('sik.laporan.*') ? 'active' : '' }}">
        <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
        Laporan SIK
    </a>
    @endcan
@endcanany
