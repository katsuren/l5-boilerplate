<?php
    $pager->appends(Request::except('page'));
?>
@if ($pager->total() > $pager->perPage())
    <nav>
        <ul class="pagination">
            @if (with($prev = $pager->previousPageUrl()))
                <li class="page-item">
                    <a href="{{ $prev }}" class="page-link">{!! __('pagination.previous') !!}</a>
                </li>
            @else
                <li class="page-item disabled">
                    <span class="page-link">{!! __('pagination.previous') !!}</span>
                </li>
            @endif

            @php($current = $pager->currentPage())
            @for ($i = max($current - 3, 1); $i <= min($current + 3, $pager->lastPage()); $i++)
                @if ($current == $i)
                    <li class="page-item active">
                        <span class="page-link">
                            {{ $i }}
                            <span class="sr-only">(current)</span>
                        </span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $pager->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            @if (with($next = $pager->nextPageUrl()))
                <li class="page-item">
                    <a href="{{ $next }}" class="page-link">{!! __('pagination.next') !!}</a>
                </li>
            @else
                <li class="page-item">
                    <span class="page-link">{!! __('pagination.next') !!}</span>
                </li>
            @endif
        </ul>
    </nav>
@endif

