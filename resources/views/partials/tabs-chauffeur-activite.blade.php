@php($tab = 'rounded-lg px-4 py-1.5 text-sm font-medium transition')
@php($tabOn = 'bg-lagon text-white')
@php($tabOff = 'text-nuit/70 hover:bg-sable-50')
<div class="mb-6 inline-flex rounded-xl border border-sable-200 bg-white p-1 shadow-soft">
    <a href="{{ route('taximan.courses.index') }}"
       class="{{ $tab }} {{ request()->routeIs('taximan.courses.*') ? $tabOn : $tabOff }}">Mes courses</a>
    <a href="{{ route('taximan.activites.index') }}"
       class="{{ $tab }} {{ request()->routeIs('taximan.activites.*') ? $tabOn : $tabOff }}">Activites</a>
</div>
