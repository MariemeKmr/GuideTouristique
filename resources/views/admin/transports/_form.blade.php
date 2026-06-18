@if ($errors->any())
    <div class="mb-4 rounded-md bg-red-50 border border-red-200 px-4 py-3">
        <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label for="methode" class="block text-sm font-medium text-gray-700 mb-1">Méthode</label>
        <input id="methode" name="methode" type="text"
               value="{{ old('methode', $transport->methode ?? '') }}" required
               placeholder="Taxi, bus, location de voiture…"
               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
    </div>

    <div>
        <label for="approximation_cout" class="block text-sm font-medium text-gray-700 mb-1">
            Coût approximatif <span class="text-gray-400 font-normal">(optionnel)</span>
        </label>
        <input id="approximation_cout" name="approximation_cout" type="text"
               value="{{ old('approximation_cout', $transport->approximation_cout ?? '') }}"
               placeholder="2 000 FCFA, 5–10 €…"
               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
            Description <span class="text-gray-400 font-normal">(optionnel)</span>
        </label>
        <textarea id="description" name="description" rows="5"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">{{ old('description', $transport->description ?? '') }}</textarea>
    </div>
</div>
