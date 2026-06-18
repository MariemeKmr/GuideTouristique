<nav class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex items-center justify-between h-16">
            <div class="flex items-center gap-8">
                <a href="{{ route('dashboard') }}" class="text-lg font-semibold text-gray-900">
                    Guide Touristique
                </a>

                {{-- Liens de gestion réservés à l'administrateur --}}
                @if (auth()->user()->isAdmin())
                    <div class="hidden sm:flex items-center gap-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="rounded-md px-3 py-1.5 text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                            Tableau de bord
                        </a>
                        <a href="{{ route('admin.destinations.index') }}"
                           class="rounded-md px-3 py-1.5 text-sm font-medium {{ request()->routeIs('admin.destinations.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                            Destinations
                        </a>
                        <a href="{{ route('admin.transports.index') }}"
                           class="rounded-md px-3 py-1.5 text-sm font-medium {{ request()->routeIs('admin.transports.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50' }}">
                            Transports
                        </a>
                    </div>
                @endif
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right leading-tight hidden sm:block">
                    <div class="text-sm font-medium text-gray-900">{{ auth()->user()->full_name }}</div>
                    <div class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="rounded-md border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Déconnexion
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>
