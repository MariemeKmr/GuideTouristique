<nav class="relative z-10 bg-lagon text-white shadow-sm">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-white">
                    Guide Touristique
                </a>

                @php($link = 'rounded-md px-3 py-1.5 text-sm font-medium transition')
                @php($active = 'bg-white/20 text-white')
                @php($idle = 'text-white/80 hover:bg-white/10 hover:text-white')

                {{-- Administrateur --}}
                @if (auth()->user()->isAdmin())
                    <div class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('admin.dashboard') }}" class="{{ $link }} {{ request()->routeIs('admin.dashboard') ? $active : $idle }}">Tableau de bord</a>
                        <a href="{{ route('admin.destinations.index') }}" class="{{ $link }} {{ request()->routeIs('admin.destinations.*') ? $active : $idle }}">Destinations</a>
                        <a href="{{ route('admin.transports.index') }}" class="{{ $link }} {{ request()->routeIs('admin.transports.*') ? $active : $idle }}">Transports</a>
                    </div>
                @endif

                {{-- Visiteur --}}
                @if (auth()->user()->isVisiteur())
                    <div class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('visitor.destinations.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.destinations.*') ? $active : $idle }}">Destinations</a>
                        <a href="{{ route('visitor.visits') }}" class="{{ $link }} {{ request()->routeIs('visitor.visits') ? $active : $idle }}">Mes visites</a>
                        <a href="{{ route('visitor.transports.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.transports.*') ? $active : $idle }}">Transports</a>
                        <a href="{{ route('visitor.drivers.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.drivers.*') ? $active : $idle }}">Chauffeurs</a>
                    </div>
                @endif

                {{-- Chauffeur --}}
                @if (auth()->user()->isTaximan())
                    <div class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('taximan.dashboard') }}" class="{{ $link }} {{ request()->routeIs('taximan.dashboard') ? $active : $idle }}">Tableau de bord</a>
                        <a href="{{ route('taximan.profile.edit') }}" class="{{ $link }} {{ request()->routeIs('taximan.profile.*') ? $active : $idle }}">Mon profil</a>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right leading-tight hidden sm:block">
                    <div class="text-sm font-medium text-white">{{ auth()->user()->full_name }}</div>
                    <div class="text-xs text-white/70 capitalize">{{ auth()->user()->role }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="rounded-md border border-white/40 px-3 py-1.5 text-sm font-medium text-white hover:bg-white/10 transition">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
