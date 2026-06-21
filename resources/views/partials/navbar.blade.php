<nav class="sticky top-0 z-30 bg-lagon/95 backdrop-blur text-white shadow-soft">
    @php($u = auth()->user())
    @php($link = 'rounded-full px-3.5 py-1.5 text-sm font-medium transition')
    @php($active = 'bg-white/20 text-white')
    @php($idle = 'text-white/80 hover:bg-white/10 hover:text-white')
    @php($mlink = 'block rounded-xl px-3 py-2 text-sm font-medium transition')
    @php($midle = 'text-white/85 hover:bg-white/10')

    {{-- Compteurs (reutilises sur desktop et mobile) --}}
    @php($signNonLus = $u->isAdmin() ? \App\Models\Signalement::where('lu', false)->count() : 0)
    @if ($u->isVisiteur())
        @php($badgeVisiteur = $u->coursesVisiteur()->where('statut', 'prix_propose')->count()
            + $u->coursesVisiteur()->where('statut', 'attente_client')->count()
            + $u->coursesVisiteur()->where('statut', 'terminee')->whereNull('note')->count()
            + $u->messagesObjetNonLus())
    @endif
    @if ($u->isTaximan())
        @php($badgeChauffeur = $u->coursesChauffeur()->where('statut', 'demandee')->count()
            + $u->coursesChauffeur()->where('statut', 'contre_propose')->count()
            + $u->coursesChauffeur()->where('alerte_chauffeur', true)->count()
            + $u->messagesObjetNonLus())
    @endif
    @if ($u->isVisiteur() || $u->isTaximan())
        @php($mesSignalements = $u->signalementsAuteur()->count())
        @php($signMsgNonLus = $u->signalementsMessagesNonLus())
    @endif

    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 text-lg font-bold tracking-tight text-white">
                    <span class="grid h-8 w-8 place-items-center rounded-xl bg-white/15 text-sm">GT</span>
                    Guide Touristique
                </a>

                {{-- Liens desktop --}}
                @if ($u->isAdmin())
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('admin.dashboard') }}" class="{{ $link }} {{ request()->routeIs('admin.dashboard') ? $active : $idle }}">Tableau de bord</a>
                        <a href="{{ route('admin.destinations.index') }}" class="{{ $link }} {{ request()->routeIs('admin.destinations.*') ? $active : $idle }}">Destinations</a>
                        <a href="{{ route('admin.transports.index') }}" class="{{ $link }} {{ request()->routeIs('admin.transports.*') ? $active : $idle }}">Transports</a>
                        <a href="{{ route('admin.activites.index') }}" class="{{ $link }} {{ request()->routeIs('admin.activites.*') ? $active : $idle }}">Activites</a>
                        <a href="{{ route('admin.signalements.index') }}" class="{{ $link }} {{ request()->routeIs('admin.signalements.*') ? $active : $idle }}">
                            Signalements
                            @if ($signNonLus > 0)
                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $signNonLus }}</span>
                            @endif
                        </a>
                    </div>
                @endif

                @if ($u->isVisiteur())
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('visitor.destinations.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.destinations.*') || request()->routeIs('visitor.transports.*') || request()->routeIs('visitor.activites.*') ? $active : $idle }}">Decouvrir</a>
                        <a href="{{ route('visitor.visits') }}" class="{{ $link }} {{ request()->routeIs('visitor.visits') ? $active : $idle }}">Mes visites</a>
                        <a href="{{ route('visitor.drivers.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.drivers.*') ? $active : $idle }}">Chauffeurs</a>
                        <a href="{{ route('visitor.courses.index') }}" class="{{ $link }} {{ request()->routeIs('visitor.courses.*') ? $active : $idle }}">
                            Mes courses
                            @if ($badgeVisiteur > 0)
                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $badgeVisiteur }}</span>
                            @endif
                        </a>
                        @if ($mesSignalements > 0)
                            <a href="{{ route('signalements.mes') }}" class="{{ $link }} {{ request()->routeIs('signalements.mes') ? $active : $idle }}">
                                Mes signalements
                                @if ($signMsgNonLus > 0)<span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $signMsgNonLus }}</span>@endif
                            </a>
                        @endif
                    </div>
                @endif

                @if ($u->isTaximan())
                    <div class="hidden md:flex items-center gap-1">
                        <a href="{{ route('taximan.dashboard') }}" class="{{ $link }} {{ request()->routeIs('taximan.dashboard') ? $active : $idle }}">Tableau de bord</a>
                        <a href="{{ route('taximan.profile.edit') }}" class="{{ $link }} {{ request()->routeIs('taximan.profile.*') ? $active : $idle }}">Mon profil</a>
                        <a href="{{ route('taximan.courses.index') }}" class="{{ $link }} {{ request()->routeIs('taximan.courses.*') || request()->routeIs('taximan.activites.*') ? $active : $idle }}">
                            Mes courses
                            @if ($badgeChauffeur > 0)
                                <span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $badgeChauffeur }}</span>
                            @endif
                        </a>
                        @if ($mesSignalements > 0)
                            <a href="{{ route('signalements.mes') }}" class="{{ $link }} {{ request()->routeIs('signalements.mes') ? $active : $idle }}">
                                Mes signalements
                                @if ($signMsgNonLus > 0)<span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $signMsgNonLus }}</span>@endif
                            </a>
                        @endif
                        <a href="{{ route('taximan.clients.index') }}" class="{{ $link }} {{ request()->routeIs('taximan.clients.*') ? $active : $idle }}">Mes clients</a>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-3">
                <div class="text-right leading-tight hidden sm:block">
                    <div class="text-sm font-semibold text-white">{{ $u->full_name }}</div>
                    <div class="text-xs text-white/70 capitalize">{{ $u->role }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
                    @csrf
                    <button type="submit"
                            class="rounded-full border border-white/40 px-4 py-1.5 text-sm font-medium text-white hover:bg-white/10 transition">
                        Déconnexion
                    </button>
                </form>

                {{-- Bouton menu mobile --}}
                <button type="button" aria-label="Ouvrir le menu"
                        onclick="document.getElementById('gt-mobile-menu').classList.toggle('hidden')"
                        class="md:hidden grid h-10 w-10 place-items-center rounded-xl text-white hover:bg-white/10 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="h-6 w-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Menu mobile --}}
    <div id="gt-mobile-menu" class="md:hidden hidden border-t border-white/10">
        <div class="px-4 py-3 space-y-1">
            <div class="px-3 py-2">
                <div class="text-sm font-semibold text-white">{{ $u->full_name }}</div>
                <div class="text-xs text-white/60 capitalize">{{ $u->role }}</div>
            </div>

            @if ($u->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="{{ $mlink }} {{ request()->routeIs('admin.dashboard') ? $active : $midle }}">Tableau de bord</a>
                <a href="{{ route('admin.destinations.index') }}" class="{{ $mlink }} {{ request()->routeIs('admin.destinations.*') ? $active : $midle }}">Destinations</a>
                <a href="{{ route('admin.transports.index') }}" class="{{ $mlink }} {{ request()->routeIs('admin.transports.*') ? $active : $midle }}">Transports</a>
                <a href="{{ route('admin.activites.index') }}" class="{{ $mlink }} {{ request()->routeIs('admin.activites.*') ? $active : $midle }}">Activites</a>
                <a href="{{ route('admin.signalements.index') }}" class="{{ $mlink }} {{ request()->routeIs('admin.signalements.*') ? $active : $midle }}">
                    Signalements
                    @if ($signNonLus > 0)
                        <span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $signNonLus }}</span>
                    @endif
                </a>
            @endif

            @if ($u->isVisiteur())
                <a href="{{ route('visitor.destinations.index') }}" class="{{ $mlink }} {{ request()->routeIs('visitor.destinations.*') || request()->routeIs('visitor.transports.*') || request()->routeIs('visitor.activites.*') ? $active : $midle }}">Decouvrir</a>
                <a href="{{ route('visitor.visits') }}" class="{{ $mlink }} {{ request()->routeIs('visitor.visits') ? $active : $midle }}">Mes visites</a>
                <a href="{{ route('visitor.drivers.index') }}" class="{{ $mlink }} {{ request()->routeIs('visitor.drivers.*') ? $active : $midle }}">Chauffeurs</a>
                <a href="{{ route('visitor.courses.index') }}" class="{{ $mlink }} {{ request()->routeIs('visitor.courses.*') ? $active : $midle }}">
                    Mes courses
                    @if ($badgeVisiteur > 0)
                        <span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $badgeVisiteur }}</span>
                    @endif
                </a>
                @if ($mesSignalements > 0)
                    <a href="{{ route('signalements.mes') }}" class="{{ $mlink }} {{ request()->routeIs('signalements.mes') ? $active : $midle }}">
                        Mes signalements
                        @if ($signMsgNonLus > 0)<span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $signMsgNonLus }}</span>@endif
                    </a>
                @endif
            @endif

            @if ($u->isTaximan())
                <a href="{{ route('taximan.dashboard') }}" class="{{ $mlink }} {{ request()->routeIs('taximan.dashboard') ? $active : $midle }}">Tableau de bord</a>
                <a href="{{ route('taximan.profile.edit') }}" class="{{ $mlink }} {{ request()->routeIs('taximan.profile.*') ? $active : $midle }}">Mon profil</a>
                <a href="{{ route('taximan.courses.index') }}" class="{{ $mlink }} {{ request()->routeIs('taximan.courses.*') || request()->routeIs('taximan.activites.*') ? $active : $midle }}">
                    Mes courses
                    @if ($badgeChauffeur > 0)
                        <span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $badgeChauffeur }}</span>
                    @endif
                </a>
                @if ($mesSignalements > 0)
                    <a href="{{ route('signalements.mes') }}" class="{{ $mlink }} {{ request()->routeIs('signalements.mes') ? $active : $midle }}">
                        Mes signalements
                        @if ($signMsgNonLus > 0)<span class="ml-1 inline-flex items-center justify-center rounded-full bg-terracotta px-1.5 text-xs font-semibold text-white">{{ $signMsgNonLus }}</span>@endif
                    </a>
                @endif
                <a href="{{ route('taximan.clients.index') }}" class="{{ $mlink }} {{ request()->routeIs('taximan.clients.*') ? $active : $midle }}">Mes clients</a>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="pt-2">
                @csrf
                <button type="submit"
                        class="w-full rounded-xl border border-white/40 px-4 py-2 text-sm font-medium text-white hover:bg-white/10 transition">
                    Déconnexion
                </button>
            </form>
        </div>
    </div>
</nav>
