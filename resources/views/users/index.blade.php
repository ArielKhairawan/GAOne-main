@extends('layouts.app')

@section('title', 'User Management')

@section('content')

<div class="d-flex justify-content-end align-items-center mb-4">
    <div class="d-flex align-items-center gap-2">
        <a class="btn btn-sm" href="{{ route('users.export') }}" style="background: rgba(16, 185, 129, 0.1); color: #10B981; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z"/></svg>
            Export CSV
        </a>
        <a class="btn btn-sm" href="{{ route('users.create') }}" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Tambah User
        </a>
    </div>
</div>

<div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); overflow: hidden;">
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="font-family: 'Poppins', sans-serif; font-size: 13.5px; width: 100%;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid var(--border);">
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Nama</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Email</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Departemen</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Jabatan</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px;">Role</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; width: 12%;">Status</th>
                    <th style="padding: 16px 24px; font-weight: 700; color: #475569; text-transform: uppercase; font-size: 11px; letter-spacing: 0.5px; text-align: right; width: 18%;">Aksi</th>
                </tr>
            </thead>
            <tbody style="border-top: none;">
                @foreach($users as $user)
                <tr style="border-bottom: 1px solid #F1F5F9; transition: background 0.2s ease;" onmouseover="this.style.backgroundColor='#F8FAFC'" onmouseout="this.style.backgroundColor='transparent'">
                    <td style="padding: 18px 24px; font-weight: 700; color: #0F172A;">
                        {{ $user->name }}
                    </td>
                    <td style="padding: 18px 24px; color: #64748B; font-weight: 500;">
                        {{ $user->email }}
                    </td>
                    <td style="padding: 18px 24px; color: #475569; font-weight: 500;">
                        {{ $user->department }}
                    </td>
                    <td style="padding: 18px 24px; color: #475569;">
                        {{ $user->position }}
                    </td>
                    <td style="padding: 18px 24px;">
                        <span style="font-size: 12px; background: #F1F5F9; color: #475569; padding: 4px 10px; border-radius: 6px; font-weight: 600;">
                            {{ $user->roles->pluck('name')->join(', ') ?: '—' }}
                        </span>
                    </td>
                    <td style="padding: 18px 24px;">
                        @if($user->is_active)
                            <span style="background: rgba(16,185,129,0.1); color: #10B981; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">Aktif</span>
                        @else
                            <span style="background: rgba(239,68,68,0.1); color: #EF4444; padding: 6px 12px; border-radius: 30px; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.3px;">Nonaktif</span>
                        @endif
                    </td>
                    <td style="padding: 18px 24px; text-align: right;">
                        <div class="d-flex gap-2 justify-content-end">
                            <a class="btn btn-sm" href="{{ route('users.edit', $user) }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 6px 14px; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px; transition: all 0.2s;">
                                <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Edit
                            </a>
                            <form class="d-inline" method="POST" action="{{ $user->is_active ? route('users.deactivate', $user) : route('users.activate', $user) }}">
                                @csrf
                                <button class="btn btn-sm" style="background: #ffffff; color: {{ $user->is_active ? '#F59E0B' : '#10B981' }}; border: 1px solid {{ $user->is_active ? '#FDE68A' : '#A7F3D0' }}; font-weight: 600; padding: 6px 14px; border-radius: 6px; transition: all 0.2s;">
                                    {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4" style="font-family: 'Poppins', sans-serif;">
    {{ $users->links() }}
</div>

@endsection
