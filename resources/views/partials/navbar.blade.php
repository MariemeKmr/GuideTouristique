<nav class="sticky top-0 z-30 bg-lagon/95 backdrop-blur text-white shadow-soft">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-lg font-bold tracking-tight text-white">
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-white/15 text-sm">GT</span>
                    Guide Touristique
                </a>

                @php($link = 'rounded-full px-3.5 py-1.5 text-sm font-medium transition')
                @php($active = 'bg-white/20 text-white')
                @php($idle = 'text-white/80 hover:bg-white/10 hover:text-white')

                @if (auth()->user()->isAdmin())
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('admin.dashboard') }}" class="{{ $link }} {{ request()->routeIs('admin.dashboard') ? $active : $idle }}">Tableau de bord</a>
                        <a href="{{ route('admin.destinations.index') }}" class="{{ $link }} {{ request()->routeIs('admin.destinations.*') ? $active : $idle }}">Destinations</a>
                        <a href="{{ route('admin.transports.index') }}" class="{{ $link }} {{ request()->routeIs('admin.transports.*') ? $active : $idle }}">Transports</a>
                        <a href="{{ route('admin.activites.index') }}" class="{{ $link }} {{ request()->routeIs('admin.activites.*') ? $active : $idle }}">Activites</a>
                    </div>
                @endif

                @if (auth()->user()->isVisiteur())
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('visitor.destinations.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.destinations.*') ? $active : $idle }}">Destinations</a>
                        <a href="{{ route('visitor.visits') }}" class="{{ $link }} {{ request()->routeIs('visitor.visits') ? $active : $idle }}">Mes visites</a>
                        <a href="{{ route('visitor.transports.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.transports.*') ? $active : $idle }}">Transports</a>
                        <a href="{{ route('visitor.activites.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.activites.*') ? $active : $idle }}">Activites</a>
                        <a href="{{ route('visitor.drivers.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.drivers.*') ? $active : $idle }}">Chauffeurs</a>
                    </div>
                @endif

                @if (auth()->user()->isTaximan())
                    @php($nonLues = auth()->user()->demandesRecues()->where('lu', false)->count())
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('taximan.dashboard') }}" class="{{ $link }} {{ request()->routeIs('taximan.dashboard') ? $active : $idle }}">
                            Tableau de bord
                            @if ($nonLues > 0)
                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $nonLues }}</span>
                            @endif
                        </a>
                        <a href="{{ route('taximan.profile.edit') }}" class="{{ $link }} {{ request()->routeIs('taximan.profile.*') ? $active : $idle }}">Mon profil</a>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right leading-tight hidden sm:block">
                    <div class="text-sm font-semibold text-white">{{ auth()->user()->full_name }}</div>
                    <div class="text-xs text-white/70 capitalize">{{ auth()->user()->role }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="rounded-full border border-white/40 px-4 py-1.5 text-sm font-medium text-white hover:bg-white/10 transition">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
