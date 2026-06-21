{{-- Attendu : $action, $q. Optionnel : $placeholder, $hidden (tableau name=>valeur), $resetUrl --}}
<form method="GET" action="{{ $action }}" class="mt-6 flex flex-wrap items-center gap-2">
    @foreach (($hidden ?? []) as $hName => $hValue)
        @if ($hValue !== null && $hValue !== '')
            <input type="hidden" name="{{ $hName }}" value="{{ $hValue }}">
        @endif
    @endforeach
    <input type="search" name="q" value="{{ $q }}" placeholder="{{ $placeholder ?? 'Rechercher...' }}"
           class="flex-1 min-w-[220px] rounded-xl border border-sable-300 bg-white px-4 py-2.5 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
    <button type="submit" class="rounded-xl bg-lagon px-5 py-2.5 text-sm font-semibold text-white hover:bg-lagon-600 transition">Rechercher</button>
    @if ($q !== '')
        <a href="{{ $resetUrl ?? $action }}" class="text-sm text-nuit/50 hover:underline">Effacer</a>
    @endif
</form>
