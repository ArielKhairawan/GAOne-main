@extends('layouts.app')

@section('title', $user->exists ? 'Edit User' : 'Tambah User')

@section('content')

<!-- Header Form (Tombol Kembali di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('users.index') }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 13.5px;">
        <strong>Periksa kembali isian Anda:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<form method="post" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}">
    @csrf
    @if($user->exists)
        @method('PUT')
    @endif

    {{-- Kartu Utama: Informasi Pengguna --}}
    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px; font-family: 'Poppins', sans-serif;">
        <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px; height:18px; color:#3B82F6;">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Informasi Pengguna
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Nama Lengkap <span style="color:#EF4444">*</span></label>
                <input class="form-control" name="name" value="{{ old('name', $user->name) }}" placeholder="Nama lengkap" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                @error('name')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Email <span style="color:#EF4444">*</span></label>
                <input class="form-control" name="email" type="email" value="{{ old('email', $user->email) }}" placeholder="nama@perusahaan.com" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                @error('email')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Nomor HP</label>
                <input class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="08xx-xxxx-xxxx" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                @error('phone')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Departemen <span style="color:#EF4444">*</span></label>
                <input class="form-control" name="department" value="{{ old('department', $user->department) }}" placeholder="General Affairs" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                @error('department')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Jabatan <span style="color:#EF4444">*</span></label>
                <input class="form-control" name="position" value="{{ old('position', $user->position) }}" placeholder="Staff GA" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                @error('position')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">
                    Password {{ $user->exists ? '' : '*' }}
                    @if($user->exists)
                        <span style="font-size:11px; color:#94A3B8; font-weight:400;">(kosongkan jika tidak diubah)</span>
                    @endif
                </label>
                <input class="form-control" name="password" type="password" placeholder="{{ $user->exists ? 'Isi untuk mengubah' : 'min. 8 karakter' }}" {{ $user->exists ? '' : 'required' }} style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                @error('password')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Kartu Utama: Hak Akses & Status --}}
    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px; font-family: 'Poppins', sans-serif;">
        <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px; height:18px; color:#F59E0B;">
                <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
            </svg>
            Hak Akses & Status Akun
        </div>

        <!-- Pilihan Peran/Roles -->
        <div class="mb-4">
            <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Role / Peran Pengguna</label>
            <div class="d-flex flex-wrap gap-2 mt-2">
                @foreach($roles as $role)
                <label style="display: flex; align-items: center; gap: 8px; padding: 8px 14px; border: 1px solid #E2E8F0; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; color: #475569; transition: all 0.2s; background: #ffffff;"
                       onmouseover="this.style.borderColor='#3B82F6'; this.style.backgroundColor='#F8FAFC';" onmouseout="this.style.borderColor='#E2E8F0'; this.style.backgroundColor='#ffffff';">
                    <input type="checkbox" name="roles[]" value="{{ $role }}" @checked($user->hasRole($role)) style="width: 15px; height: 15px; accent-color: #3B82F6; cursor: pointer;">
                    {{ $role }}
                </label>
                @endforeach
            </div>
            @error('roles')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
        </div>

        <hr style="border-top: 1px solid #F1F5F9; margin: 20px 0;">

        <!-- Status Keaktifan -->
        <div>
            <input type="hidden" name="is_active" value="0">
            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer; user-select: none;">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active ?? true)) style="width: 18px; height: 18px; accent-color: #10B981; cursor: pointer;">
                <span style="font-size: 13.5px; font-weight: 600; color: #1E293B;">Aktifkan akun pengguna ini</span>
            </label>
            <p style="font-size: 12px; color: #64748B; margin-top: 4px; margin-left: 28px; line-height: 1.4;">
                Pengguna yang tidak aktif akan diblokir dan tidak dapat masuk/login ke dalam sistem.
            </p>
        </div>
    </div>

    <!-- Tombol Aksi Form -->
    <div class="d-flex align-items-center gap-2" style="font-family: 'Poppins', sans-serif;">
        <button type="submit" class="btn btn-sm" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 12px 28px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
            </svg>
            Simpan Pengguna
        </button>
        <a class="btn btn-sm" href="{{ route('users.index') }}" style="background: #F1F5F9; color: #475569; border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
            Batal
        </a>
    </div>
</form>

@endsection
