@if ($paginator->hasPages())
<ul class="pagination">
  @if ($paginator->onFirstPage())
    <li class="disabled">‹ Prev</li>
  @else
    <li><a href="{{ $paginator->previousPageUrl() }}">‹ Prev</a></li>
  @endif
  <li class="disabled">Page {{ $paginator->currentPage() }} of {{ $paginator->lastPage() }}</li>
  @if ($paginator->hasMorePages())
    <li><a href="{{ $paginator->nextPageUrl() }}">Next ›</a></li>
  @else
    <li class="disabled">Next ›</li>
  @endif
</ul>
@endif
