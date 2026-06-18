@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigation des pages" class="flex items-center justify-between">
        {{-- Mobile --}}
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="rounded-xl border border-sable-200 px-3 py-2 text-sm text-nuit/40">Precedent</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="rounded-xl border border-sable-300 px-3 py-2 text-sm text-nuit/80 hover:bg-sable-50">Precedent</a>
            @endif
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="rounded-xl border border-sable-300 px-3 py-2 text-sm text-nuit/80 hover:bg-sable-50">Suivant</a>
            @else
                <span class="rounded-xl border border-sable-200 px-3 py-2 text-sm text-nuit/40">Suivant</span>
            @endif
        </div>

        {{-- Bureau --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <p class="text-sm text-nuit/60">
                Affichage de
                <span class="font-medium text-nuit">{{ $paginator->firstItem() }}</span>
                a
                <span class="font-medium text-nuit">{{ $paginator->lastItem() }}</span>
                sur
                <span class="font-medium text-nuit">{{ $paginator->total() }}</span>
                resultats
            </p>

            <span class="inline-flex items-center gap-1">
                {{-- Precedent --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" class="grid h-9 w-9 place-items-center rounded-lg border border-sable-200 text-nuit/30">&lsaquo;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="grid h-9 w-9 place-items-center rounded-lg border border-sable-300 text-nuit/70 hover:bg-sable-50">&lsaquo;</a>
                @endif

                {{-- Numeros --}}
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="grid h-9 min-w-9 place-items-center px-2 text-nuit/40">{{ $element }}</span>
                    @endif
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="grid h-9 min-w-9 place-items-center rounded-lg bg-lagon px-2 text-sm font-semibold text-white">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="grid h-9 min-w-9 place-items-center rounded-lg border border-sable-300 px-2 text-sm text-nuit/70 hover:bg-sable-50">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Suivant --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="grid h-9 w-9 place-items-center rounded-lg border border-sable-300 text-nuit/70 hover:bg-sable-50">&rsaquo;</a>
                @else
                    <span aria-disabled="true" class="grid h-9 w-9 place-items-center rounded-lg border border-sable-200 text-nuit/30">&rsaquo;</span>
                @endif
            </span>
        </div>
    </nav>
@endif
