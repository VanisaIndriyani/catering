@php
    $pesan = [];
    if (session('success')) $pesan[] = ['ok', session('success')];
    if (session('error')) $pesan[] = ['bad', session('error')];
    if (session('info')) $pesan[] = ['info', session('info')];
    if (isset($errors) && $errors->any()) $pesan[] = ['bad', $errors->first()];
@endphp
<div class="toasts" id="toasts" aria-live="polite">
    @foreach ($pesan as [$jenis, $teks])
        <div class="toast {{ $jenis }}" data-toast>
            <span>{{ $teks }}</span>
            <button type="button" aria-label="Tutup" onclick="this.parentElement.remove()">×</button>
        </div>
    @endforeach
</div>
<script>
document.querySelectorAll('[data-toast]').forEach((el) => {
    setTimeout(() => el.classList.add('out'), 4200);
    setTimeout(() => el.remove(), 4700);
});
</script>
