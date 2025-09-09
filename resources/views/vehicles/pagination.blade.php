@if ($paginator->hasPages())
    <nav class="flex items-center justify-between border-t border-neutral-200 bg-white px-4 py-3 sm:px-6" aria-label="Pagination">
        <div class="hidden sm:block">
            <p class="text-sm text-neutral-700">
                Hiển thị
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                đến
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                trong tổng số
                <span class="font-medium">{{ $paginator->total() }}</span>
                kết quả
            </p>
        </div>
        <div class="flex flex-1 justify-between sm:justify-end">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <span class="relative inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-500 cursor-not-allowed">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Trước
                </span>
            @else
                @php
                    $currentRoute = request()->route()->getName();
                    $baseRoute = str_replace('.page', '', $currentRoute);
                    $previousPage = $paginator->currentPage() - 1;
                @endphp
                <a href="{{ route($baseRoute . '.page', $previousPage) }}" class="relative inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50">
                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Trước
                </a>
            @endif

            {{-- Pagination Elements --}}
            <div class="hidden sm:flex sm:items-center sm:space-x-2">
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-brand-600 border border-brand-600 rounded-md">{{ $page }}</span>
                            @else
                                @php
                                    $currentRoute = request()->route()->getName();
                                    $baseRoute = str_replace('.page', '', $currentRoute);
                                @endphp
                                <a href="{{ route($baseRoute . '.page', $page) }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-neutral-700 bg-white border border-neutral-300 rounded-md hover:bg-neutral-50">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                @php
                    $currentRoute = request()->route()->getName();
                    $baseRoute = str_replace('.page', '', $currentRoute);
                    $nextPage = $paginator->currentPage() + 1;
                @endphp
                <a href="{{ route($baseRoute . '.page', $nextPage) }}" class="relative ml-3 inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-700 hover:bg-neutral-50">
                    Sau
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            @else
                <span class="relative ml-3 inline-flex items-center rounded-md border border-neutral-300 bg-white px-4 py-2 text-sm font-medium text-neutral-500 cursor-not-allowed">
                    Sau
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
