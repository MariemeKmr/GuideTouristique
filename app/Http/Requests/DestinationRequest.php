<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DestinationRequest extends FormRequest
{
    /**
     * L'accès est déjà restreint par le middleware 'role:admin' sur la route.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Règles de validation (identiques en création et en modification).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'localite'    => ['required', 'string', 'max:255'],
            'rue'         => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name'        => 'nom',
            'localite'    => 'localité',
            'rue'         => 'rue',
            'description' => 'description',
        ];
    }
}
