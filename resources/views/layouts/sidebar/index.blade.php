@unless(auth()->user()->hasRole('Security') && ! auth()->user()->hasAnyRole(['Admin', 'GA Staff']))
<div class="sidebar-group">Menu Utama</div>

<a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
    Halaman Utama
</a>
@endunless

@include('layouts.sidebar.approvals')
@include('layouts.sidebar.reports')
@include('layouts.sidebar.operasional')
@include('layouts.sidebar.inventaris')
@include('layouts.sidebar.meeting')
@include('layouts.sidebar.layanan')
@include('layouts.sidebar.sik')
@include('layouts.sidebar.admin')
