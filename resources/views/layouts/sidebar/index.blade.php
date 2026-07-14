@unless(auth()->user()->hasRole('Security') && ! auth()->user()->hasAnyRole(['Admin', 'GA Staff']))
<div class="sidebar-group">Main</div>

<a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
    Dashboard
</a>
@endunless

@include('layouts.sidebar.approvals')
@include('layouts.sidebar.operasional')
@include('layouts.sidebar.inventaris')
@include('layouts.sidebar.meeting')
@include('layouts.sidebar.layanan')
@include('layouts.sidebar.sik')
@include('layouts.sidebar.reports')
@include('layouts.sidebar.notification')
@include('layouts.sidebar.admin')
@include('layouts.sidebar.settings')
@include('layouts.sidebar.legacy')
