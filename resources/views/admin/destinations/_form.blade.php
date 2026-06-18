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
        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
        <input id="name" name="name" type="text"
               value="{{ old('name', $destination->name ?? '') }}" required
               class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="localite" class="block text-sm font-medium text-gray-700 mb-1">Localité</label>
            <input id="localite" name="localite" type="text"
                   value="{{ old('localite', $destination->localite ?? '') }}" required
                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
        </div>
        <div>
            <label for="rue" class="block text-sm font-medium text-gray-700 mb-1">
                Rue <span class="text-gray-400 font-normal">(optionnel)</span>
            </label>
            <input id="rue" name="rue" type="text"
                   value="{{ old('rue', $destination->rue ?? '') }}"
                   class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
            Description <span class="text-gray-400 font-normal">(optionnel)</span>
        </label>
        <textarea id="description" name="description" rows="5"
                  class="w-full rounded-md border border-gray-300 px-3 py-2 text-sm focus:border-gray-900 focus:ring-gray-900 focus:outline-none focus:ring-1">{{ old('description', $destination->description ?? '') }}</textarea>
    </div>
</div>
