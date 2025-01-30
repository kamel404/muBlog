@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination">
        <ul class="flex justify-center space-x-2">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li aria-disabled="true">
                    <span class="px-4 py-2 text-gray-300 cursor-default rounded-lg">
                        &laquo;
                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}"
                       class="px-4 py-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                        &laquo;
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-disabled="true">
                        <span class="px-4 py-2 text-gray-400 cursor-default rounded-lg">{{ $element }}</span>
                    </li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li aria-current="page">
                                <span class="px-4 py-2 bg-blue-600 text-white rounded-lg">{{ $page }}</span>
                            </li>
                        @else
                            <li>
                                <a href="{{ $url }}"
                                   class="px-4 py-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}"
                       class="px-4 py-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors">
                        &raquo;
                    </a>
                </li>
            @else
                <li aria-disabled="true">
                    <span class="px-4 py-2 text-gray-300 cursor-default rounded-lg">
                        &raquo;
                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
