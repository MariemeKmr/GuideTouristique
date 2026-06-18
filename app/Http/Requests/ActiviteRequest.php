<?php

namespace App\Http\Requests;

use App\Models\Activite;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActiviteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nom'            => ['required', 'string', 'max:255'],
            'categorie'      => ['required', Rule::in(array_keys(Activite::CATEGORIES))],
            'lieu'           => ['nullable', 'string', 'max:255'],
            'tarif'          => ['nullable', 'string', 'max:255'],
            'description'    => ['nullable', 'string'],
            'destination_id' => ['nullable', 'exists:destinations,id'],
        ];
    }

    public function attributes(): array
    {
        return [
            'nom'            => 'nom',
            'categorie'      => 'categorie',
            'lieu'           => 'lieu',
            'tarif'          => 'tarif',
            'description'    => 'description',
            'destination_id' => 'destination',
        ];
    }
}
