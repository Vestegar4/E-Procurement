@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="d-flex justify-content-center mt-4 pt-3 border-top">
        <ul class="pagination custom-pagination mb-0" style="gap: 8px;">
            
            {{-- Tombol Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link border-0 shadow-sm" style="border-radius: 8px; background-color: #f1f5f9; color: #94a3b8;"><i class="fa-solid fa-chevron-left"></i></span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link border shadow-sm text-dark fw-bold" href="{{ $paginator->previousPageUrl() }}" rel="prev" style="border-radius: 8px; transition: 0.2s;"><i class="fa-solid fa-chevron-left"></i></a>
                </li>
            @endif

            {{-- Elemen Nomor Halaman --}}
            @foreach ($elements as $element)
                {{-- Pemisah "Titik Tiga" --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link border-0 bg-transparent text-muted">{{ $element }}</span></li>
                @endif

                {{-- Deretan Link Halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page">
                                <span class="page-link border-0 shadow" style="border-radius: 8px; background-color: var(--color-primary); color: var(--color-white); font-weight: 700;">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link border shadow-sm text-dark fw-bold" href="{{ $url }}" style="border-radius: 8px; transition: 0.2s;">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link border shadow-sm text-dark fw-bold" href="{{ $paginator->nextPageUrl() }}" rel="next" style="border-radius: 8px; transition: 0.2s;"><i class="fa-solid fa-chevron-right"></i></a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link border-0 shadow-sm" style="border-radius: 8px; background-color: #f1f5f9; color: #94a3b8;"><i class="fa-solid fa-chevron-right"></i></span>
                </li>
            @endif
            
        </ul>
    </nav>
@endif

{{-- Sedikit CSS untuk efek hover agar lebih smooth (Bisa ditaruh di app.css juga) --}}
<style>
    .custom-pagination .page-link:hover {
        background-color: var(--color-surface);
        color: var(--color-primary) !important;
        transform: translateY(-2px);
    }
</style>