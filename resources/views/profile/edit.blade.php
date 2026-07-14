@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<!-- Header Form (Tanpa teks penumpuk, memberikan ruang nafas di atas) -->
<div class="mb-4"></div>

@if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 13.5px;">
        <strong>Periksa kembali isian Anda:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="row g-4" style="font-family: 'Poppins', sans-serif;">

    {{-- Update Profile (Melebar Penuh) --}}
    <div class="col-12">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px; height:18px; color:#3B82F6;">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                Informasi Pribadi
            </div>

            <form method="post" enctype="multipart/form-data" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Nama Lengkap <span style="color:#EF4444">*</span></label>
                        <input class="form-control" name="name" value="{{ old('name', auth()->user()->name) }}" placeholder="Nama lengkap Anda" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        @error('name')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Nomor HP</label>
                        <input class="form-control" name="phone" value="{{ old('phone', auth()->user()->phone) }}" placeholder="08xx-xxxx-xxxx" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        @error('phone')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Departemen</label>
                        <input class="form-control" name="department" value="{{ old('department', auth()->user()->department) }}" placeholder="General Affairs" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        @error('department')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Jabatan</label>
                        <input class="form-control" name="position" value="{{ old('position', auth()->user()->position) }}" placeholder="Staff GA" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        @error('position')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-12 mb-2">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Foto Profil</label>
                        <input class="form-control" name="photo" type="file" accept="image/*" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13px; border-radius: 6px; height: 38px; line-height: 26px;">
                        @error('photo')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-sm" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15); margin-top: 10px;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
                    </svg>
                    Simpan Profil
                </button>
            </form>
        </div>
    </div>

    {{-- Change Password (Melebar Penuh di Bawahnya) --}}
    <div class="col-12">
        <div class="metric-card" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px;">
            <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px; height:18px; color:#F59E0B;">
                    <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                </svg>
                Ubah Password
            </div>

            <form method="post" action="{{ route('profile.password') }}">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Password Saat Ini <span style="color:#EF4444">*</span></label>
                        <input class="form-control" name="current_password" type="password" placeholder="Password lama Anda" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        @error('current_password')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Password Baru <span style="color:#EF4444">*</span></label>
                        <input class="form-control" name="password" type="password" placeholder="Min. 8 karakter" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        @error('password')<div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>@enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">Konfirmasi Password Baru <span style="color:#EF4444">*</span></label>
                        <input class="form-control" name="password_confirmation" type="password" placeholder="Ulangi password baru" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                    </div>
                </div>

                <button type="submit" class="btn btn-sm" style="background: #F1F5F9; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; margin-top: 24px;">
                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                        <path d="M18 8h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zm-6 9c-1.1 0-2-.9-2-2s.9-2 2-2 2 .9 2 2-.9 2-2 2zm3.1-9H8.9V6c0-1.71 1.39-3.1 3.1-3.1 1.71 0 3.1 1.39 3.1 3.1v2z"/>
                    </svg>
                    Ubah Password
                </button>
            </form>
        </div>
    </div>

</div>

@endsection
