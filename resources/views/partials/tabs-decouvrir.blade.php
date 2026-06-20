@php($tab = 'rounded-lg px-4 py-1.5 text-sm font-medium transition')
@php($tabOn = 'bg-lagon text-white')
@php($tabOff = 'text-nuit/70 hover:bg-sable-50')
<div class="mb-6 inline-flex rounded-xl border border-sable-200 bg-white p-1 shadow-soft">
    <a href="{{ route('visitor.destinations.index') }}"
       class="{{ $tab }} {{ request()->routeIs('visitor.destinations.*') ? $tabOn : $tabOff }}">Destinations</a>
    <a href="{{ route('visitor.transports.index') }}"
       class="{{ $tab }} {{ request()->routeIs('visitor.transports.*') ? $tabOn : $tabOff }}">Transports</a>
</div>
