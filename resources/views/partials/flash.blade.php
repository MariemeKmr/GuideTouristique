@if (session('success'))
    <div class="mb-6 rounded-2xl bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-800">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-6 rounded-2xl bg-terracotta/10 border border-terracotta/30 px-4 py-3 text-sm text-terracotta-700">
        {{ session('error') }}
    </div>
@endif
