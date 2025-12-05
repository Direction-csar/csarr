@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination" class="pagination-csar">
    <ul class="pagination-list">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="pagination-item disabled" aria-disabled="true" aria-label="Précédent">
                <span class="pagination-link" aria-hidden="true"><i class="fas fa-angle-left"></i></span>
            </li>
        @else
            <li class="pagination-item">
                <a class="pagination-link" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="Précédent">
                    <i class="fas fa-angle-left"></i>
                </a>
            </li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="pagination-item disabled" aria-disabled="true"><span class="pagination-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="pagination-item active" aria-current="page"><span class="pagination-link">{{ $page }}</span></li>
                    @else
                        <li class="pagination-item"><a class="pagination-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="pagination-item">
                <a class="pagination-link" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="Suivant">
                    <i class="fas fa-angle-right"></i>
                </a>
            </li>
        @else
            <li class="pagination-item disabled" aria-disabled="true" aria-label="Suivant">
                <span class="pagination-link" aria-hidden="true"><i class="fas fa-angle-right"></i></span>
            </li>
        @endif
    </ul>
</nav>
<style>
.pagination-csar { display:flex; justify-content:center; margin-top: 1rem; }
.pagination-list { list-style:none; display:flex; gap:6px; padding:0; margin:0; }
.pagination-item {}
.pagination-link { display:inline-flex; align-items:center; justify-content:center; min-width:36px; height:36px; padding:0 10px; border-radius:9px; border:1px solid #e5e7eb; background:#fff; color:#374151; text-decoration:none; font-weight:600; }
.pagination-item .pagination-link i { font-size:14px; }
.pagination-item.active .pagination-link { background: linear-gradient(135deg, #059669 0%, #10b981 100%); color:#fff; border-color:#059669; }
.pagination-item.disabled .pagination-link { opacity:.4; cursor:not-allowed; background:#f9fafb; }
.pagination-link:hover { border-color:#059669; color:#059669; }
</style>
@endif


