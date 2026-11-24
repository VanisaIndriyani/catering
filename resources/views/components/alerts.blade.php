@if (session('success'))
    <div class="alert ok">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert bad">{{ session('error') }}</div>
@endif
@if (session('info'))
    <div class="alert info">{{ session('info') }}</div>
@endif
