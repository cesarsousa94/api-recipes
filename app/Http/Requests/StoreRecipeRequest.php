<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'ingredients' => ['nullable','array'],
            'ingredients.*' => ['string','max:255'],
            'steps'       => ['nullable','array'],
            'steps.*'     => ['string'],
            'prep_time'   => ['nullable','integer','min:0'],
            'yield'       => ['nullable','string','max:255'],
            'tags'        => ['nullable','array'],
            'tags.*'      => ['string','max:50'],
        ];
    }
}
