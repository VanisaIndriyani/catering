@props(['ikon' => 'lihat', 'label'])
@php
    $gambar = [
        'lihat' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12z"/><circle cx="12" cy="12" r="2.5"/></svg>',
        'ubah' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20h4L18.5 9.5l-4-4L4 16v4z"/><path d="M13 7l4 4"/></svg>',
        'hapus' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16"/><path d="M9 7V5h6v2"/><path d="M7 7l1 13h8l1-13"/></svg>',
        'tambah' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 5v14M5 12h14"/></svg>',
        'terima' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 13l4 4L19 7"/></svg>',
        'tolak' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 6l12 12M18 6L6 18"/></svg>',
        'status' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 7h16M4 12h16M4 17h10"/></svg>',
        'batal' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M8 8l8 8"/></svg>',
        'unggah' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 16V6M8 9l4-4 4 4M5 20h14"/></svg>',
    ];
    $tag = $attributes->has('href') ? 'a' : 'button';
@endphp
<{{ $tag }}
    @if ($tag === 'button') type="{{ $attributes->get('type', 'button') }}" @endif
    {{ $attributes->except('type')->class(['aksi', 'aksi-'.$ikon])->merge(['title' => $label, 'aria-label' => $label]) }}
>{!! $gambar[$ikon] ?? $gambar['lihat'] !!}</{{ $tag }}>
