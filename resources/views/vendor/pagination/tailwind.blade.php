@if ($paginator->hasPages())
    <div class="flex justify-center items-center mt-8">
        <nav class="flex items-center space-x-2">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <button
                    class="px-3 py-1 glass text-gray-400 dark:text-gray-600 rounded-lg font-medium cursor-not-allowed">
                    <i class="fas fa-chevron-left"></i>
                </button>
            @else
                <a wire:navigate href="{{ $paginator->previousPageUrl() }}"
                    class="px-3 py-1 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-2 text-gray-500">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span
                                class="px-3 py-1 bg-primary-600 text-white rounded-lg font-medium">{{ $page }}</span>
                        @else
                            <a wire:navigate href="{{ $url }}"
                                class="px-3 py-1 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <a wire:navigate href="{{ $paginator->nextPageUrl() }}"
                    class="px-3 py-1 glass text-gray-900 dark:text-white rounded-lg font-medium hover:bg-white/30 dark:hover:bg-black/30">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <button
                    class="px-3 py-1 glass text-gray-400 dark:text-gray-600 rounded-lg font-medium cursor-not-allowed">
                    <i class="fas fa-chevron-right"></i>
                </button>
            @endif

        </nav>
    </div>
@endif
