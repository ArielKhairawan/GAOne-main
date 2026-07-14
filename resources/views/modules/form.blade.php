@extends('layouts.app')

@section('title', ($record->exists ? 'Edit' : 'Tambah') . ' ' . $meta['title'])

@section('content')

<!-- Header Form (Tombol Kembali di Sebelah Kanan) -->
<div class="d-flex justify-content-end align-items-center mb-4">
    <a class="btn btn-sm" href="{{ route('modules.index', $meta['slug']) }}" style="background: #ffffff; color: #475569; border: 1px solid #E2E8F0; font-weight: 600; padding: 10px 18px; border-radius: 8px; font-family: 'Poppins', sans-serif; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;">
        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Kembali
    </a>
</div>

@if($errors->any())
    <div class="alert alert-danger mb-4" style="border-radius: 12px; font-family: 'Poppins', sans-serif; font-size: 13.5px;">
        <strong>Periksa kembali isian Anda:</strong>
        <ul class="mb-0 mt-2">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="post" action="{{ $record->exists ? route('modules.update', [$meta['slug'], $record->id]) : route('modules.store', $meta['slug']) }}">
    @csrf
    @if($record->exists)
        @method('PUT')
    @endif

    {{-- Kartu Utama: Informasi Dinamis (Satu Kotak Vertikal Kebawah) --}}
    <div class="metric-card mb-4" style="border-radius: 16px; background: #ffffff; border: 1px solid var(--border); box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 28px; font-family: 'Poppins', sans-serif;">
        <div style="font-size: 15px; font-weight: 700; color: #0F172A; margin-bottom: 24px; border-bottom: 1px solid #F1F5F9; padding-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" fill="currentColor" style="width:18px; height:18px; color:#3B82F6;">
                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
            </svg>
            Informasi {{ $meta['title'] }}
        </div>

        <div class="row g-4">
            @foreach($meta['fields'] as $field => $rule)
            <div class="col-12">
                <label class="form-label" style="font-size: 12px; font-weight: 600; color: #475569;">
                    {{ str($field)->headline() }}
                    @if(str_contains($rule, 'required'))
                        <span style="color:#EF4444">*</span>
                    @endif
                </label>

                @if(str_contains($rule, 'boolean'))
                    <select class="form-select" name="{{ $field }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        <option value="1" @selected(old($field, $record->$field) == 1)>Ya</option>
                        <option value="0" @selected(old($field, $record->$field) == 0 || old($field, $record->$field) === null)>Tidak</option>
                    </select>

                @elseif(str_contains($rule, 'in:') && preg_match('/in:([^|]+)/', $rule, $m))
                    <select class="form-select" name="{{ $field }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        <option value="">— Pilih —</option>
                        @foreach(explode(',', $m[1]) as $opt)
                            <option value="{{ $opt }}" @selected(old($field, $record->$field) === $opt)>
                                {{ ucfirst($opt) }}
                            </option>
                        @endforeach
                    </select>

                @elseif(str_contains($rule, 'exists:') && preg_match('/exists:(\w+),/', $rule, $m))
                    @php
                        $table = $m[1];
                        try {
                            $options = \Illuminate\Support\Facades\DB::table($table)->select('id', 'name')->orderBy('name')->get();
                        } catch (\Exception $e) {
                            $options = collect();
                        }
                    @endphp
                    <select class="form-select" name="{{ $field }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                        <option value="">— Pilih —</option>
                        @foreach($options as $opt)
                            <option value="{{ $opt->id }}" @selected(old($field, $record->$field) == $opt->id)>
                                {{ $opt->name ?? $opt->id }}
                            </option>
                        @endforeach
                    </select>

                @elseif(str_contains($rule, 'date'))
                    @php
                        $isDatetime = str_contains($field, '_at') || in_array($field, ['starts_at', 'ends_at', 'sent_at', 'completed_at', 'realized_at']);
                        $fmt = $isDatetime ? 'Y-m-d\TH:i' : 'Y-m-d';
                        $type = $isDatetime ? 'datetime-local' : 'date';
                        $val = old($field, optional($record->$field instanceof \Carbon\Carbon ? $record->$field : (is_string($record->$field) ? \Carbon\Carbon::parse($record->$field) : null))->format($fmt));
                    @endphp
                    <input class="form-control" type="{{ $type }}" name="{{ $field }}" value="{{ $val }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">

                @elseif(str_contains($rule, 'numeric'))
                    <input class="form-control" type="number" step="any" min="0" name="{{ $field }}" value="{{ old($field, $record->$field) }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">

                @elseif(str_contains($rule, 'string') && (str_contains($field, 'notes') || str_contains($field, 'description') || str_contains($field, 'purpose') || str_contains($field, 'body') || str_contains($field, 'address')))
                    <textarea class="form-control" name="{{ $field }}" rows="3" placeholder="{{ str($field)->headline() }}..." style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px;">{{ old($field, $record->$field) }}</textarea>

                @else
                    <input class="form-control" name="{{ $field }}" value="{{ old($field, $record->$field) }}" placeholder="{{ str($field)->headline() }}" style="background: #ffffff; border: 1px solid #E2E8F0; font-size: 13.5px; border-radius: 6px; height: 38px;">
                @endif

                @error($field)
                    <div style="font-size:11.5px; color:#EF4444; margin-top:4px;">{{ $message }}</div>
                @enderror
            </div>
            @endforeach
        </div>
    </div>

    <!-- Tombol Aksi Form -->
    <div class="d-flex align-items-center gap-2" style="font-family: 'Poppins', sans-serif;">
        <button type="submit" class="btn btn-sm" style="background: #3B82F6; color: #ffffff; border: none; font-weight: 600; padding: 12px 28px; border-radius: 8px; display: inline-flex; align-items: center; gap: 6px; box-shadow: 0 2px 4px rgba(59, 130, 246, 0.15);">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor">
                <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm-5 16c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-10H5V5h10v4z"/>
            </svg>
            Simpan Data
        </button>
        <a class="btn btn-sm" href="{{ route('modules.index', $meta['slug']) }}" style="background: #F1F5F9; color: #475569; border: none; font-weight: 600; padding: 12px 24px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; transition: all 0.2s;">
            Batal
        </a>
    </div>
</form>

@endsection
