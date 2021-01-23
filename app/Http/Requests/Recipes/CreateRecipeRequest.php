<?php

namespace App\Http\Requests\Recipes;

use App\Rules\PreparationTimeRule;
use App\Rules\StepsRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateRecipeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'title' => 'required|max:255',
            'description' => 'required',
            'ingredients' => 'required',
            'steps' => ['required', 'json', new StepsRule],
            'preparation_time' => ['required', 'string', new PreparationTimeRule],
            'difficulty' => 'required|integer|between:1,5',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2000',
        ];
    }
}
