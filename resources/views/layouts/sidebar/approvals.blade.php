@can('approval.view')
    <a href="{{ route('approvals.index') }}" class="{{ request()->routeIs('approvals.*') ? 'active' : '' }}">
    <svg class="nav-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20zm-1 14l-4-4 1.41-1.41L11 13.17l5.59-5.59L18 9l-7 7z"/></svg>
    Persetujuan
</a>
@endcan
