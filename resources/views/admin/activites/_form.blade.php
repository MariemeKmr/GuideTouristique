@if ($errors->any())
    <div class="mb-4 rounded-xl bg-red-50 border border-red-200 px-4 py-3">
        <ul class="text-sm text-red-700 list-disc list-inside space-y-1">
            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul>
    </div>
@endif

<div class="space-y-4">
    <div>
        <label for="nom" class="block text-sm font-medium text-nuit/80 mb-1">Nom</label>
        <input id="nom" name="nom" type="text" required
               value="{{ old('nom', $activite->nom ?? '') }}"
               class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="categorie" class="block text-sm font-medium text-nuit/80 mb-1">Categorie</label>
            <select id="categorie" name="categorie" required
                    class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm bg-white focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                @foreach ($categories as $cle => $libelle)
                    <option value="{{ $cle }}" @selected(old('categorie', $activite->categorie ?? '') === $cle)>{{ $libelle }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="lieu" class="block text-sm font-medium text-nuit/80 mb-1">Lieu <span class="text-nuit/40 font-normal">(optionnel)</span></label>
            <input id="lieu" name="lieu" type="text"
                   value="{{ old('lieu', $activite->lieu ?? '') }}"
                   class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
        </div>
    </div>

    <div class="grid grid-cols-2 gap-3">
        <div>
            <label for="tarif" class="block text-sm font-medium text-nuit/80 mb-1">Tarif <span class="text-nuit/40 font-normal">(optionnel)</span></label>
            <input id="tarif" name="tarif" type="text" placeholder="Ex : 13 000 a 15 000 FCFA"
                   value="{{ old('tarif', $activite->tarif ?? '') }}"
                   class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
        </div>
        <div>
            <label for="destination_id" class="block text-sm font-medium text-nuit/80 mb-1">Destination liee <span class="text-nuit/40 font-normal">(optionnel)</span></label>
            <select id="destination_id" name="destination_id"
                    class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm bg-white focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">
                <option value="">Aucune</option>
                @foreach ($destinations as $destination)
                    <option value="{{ $destination->id }}" @selected(old('destination_id', $activite->destination_id ?? '') === $destination->id)>{{ $destination->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div>
        <label for="description" class="block text-sm font-medium text-nuit/80 mb-1">Description <span class="text-nuit/40 font-normal">(optionnel)</span></label>
        <textarea id="description" name="description" rows="5"
                  class="w-full rounded-xl border border-sable-300 px-3 py-2 text-sm focus:border-lagon focus:ring-lagon focus:outline-none focus:ring-1">{{ old('description', $activite->description ?? '') }}</textarea>
    </div>
</div>
