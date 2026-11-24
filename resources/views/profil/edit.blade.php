@extends('layouts.app')
@section('title', 'Profil')
@section('heading', 'Profil')
@section('content')
    <div class="profile-wrap">
        <section class="home-block profile-card">
            <div class="profile-head">
                <span class="avatar">{{ strtoupper(substr($user->nama, 0, 1)) }}</span>
                <div>
                    <h3>{{ $user->nama }}</h3>
                    <p class="muted">{{ $user->labelPeran() }}</p>
                </div>
            </div>
            <form class="stack" method="post" action="{{ route('profil.update') }}">
                @csrf
                @method('PUT')
                <label class="field-block">Nama lengkap
                    <input class="plain-input" name="nama" value="{{ old('nama', $user->nama) }}" required>
                </label>
                @error('nama') <small class="err">{{ $message }}</small> @enderror
                <div class="grid-2">
                    <label class="field-block">Email
                        <input class="plain-input" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </label>
                    <label class="field-block">Username
                        <input class="plain-input" name="username" value="{{ old('username', $user->username) }}" required>
                    </label>
                </div>
                @error('email') <small class="err">{{ $message }}</small> @enderror
                @error('username') <small class="err">{{ $message }}</small> @enderror
                <label class="field-block">No. telepon
                    <input class="plain-input" name="no_telepon" value="{{ old('no_telepon', $user->no_telepon) }}">
                </label>
                @if ($user->pelanggan)
                    <label class="field-block">Instansi / alamat
                        <input class="plain-input" name="alamat_instansi" value="{{ old('alamat_instansi', $user->pelanggan->alamat_instansi) }}">
                    </label>
                @endif
                <label class="field-block">Password baru
                    <span class="pass-wrap">
                        <input class="plain-input" type="password" name="password" placeholder="Kosongkan jika tidak diganti" autocomplete="new-password">
                        <button class="eye" type="button" data-toggle-pass aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/><circle cx="12" cy="12" r="2.5"/></svg>
                        </button>
                    </span>
                </label>
                @error('password') <small class="err">{{ $message }}</small> @enderror
                <button class="btn" type="submit">Simpan Profil</button>
            </form>
        </section>
    </div>
@endsection
@push('scripts')
<script>
document.querySelectorAll('[data-toggle-pass]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const input = btn.parentElement.querySelector('input');
        const buka = input.type === 'password';
        input.type = buka ? 'text' : 'password';
        btn.setAttribute('aria-label', buka ? 'Sembunyikan password' : 'Tampilkan password');
    });
});
</script>
@endpush
