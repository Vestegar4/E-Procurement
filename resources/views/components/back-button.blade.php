{{-- resources/views/components/back-button.blade.php --}}
<a href="{{ $url ?? url()->previous() }}" class="btn btn-outline-action shadow-sm fw-bold px-3" style="border-radius: 8px;">
    <i class="fa-solid fa-arrow-left me-2"></i> {{ $label ?? 'Kembali' }}
</a>