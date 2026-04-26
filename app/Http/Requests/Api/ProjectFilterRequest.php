<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectFilterRequest extends FormRequest
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
            'search' => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
            'subcategory_id' => 'nullable|integer|exists:sub_categories,id',
            'country_id' => 'nullable|integer|exists:countries,id',
            'experience_level' => 'nullable|string|in:junior,intermediate,expert',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0|gte:min_price',
            'delivery_days' => 'nullable|integer|min:1',
            'min_rating' => 'nullable|numeric|min:0|max:5',
            'is_pro' => 'nullable|boolean',
            'sort' => 'nullable|string|in:latest,price_low,price_high,rating,popular',
            'per_page' => 'nullable|integer|min:1|max:50',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'category_id.exists' => 'The selected category does not exist.',
            'subcategory_id.exists' => 'The selected subcategory does not exist.',
            'country_id.exists' => 'The selected country does not exist.',
            'max_price.gte' => 'Maximum price must be greater than or equal to minimum price.',
            'experience_level.in' => 'Experience level must be junior, intermediate, or expert.',
            'min_rating.max' => 'Rating must be between 0 and 5.',
            'sort.in' => 'Invalid sort option.',
            'per_page.max' => 'Maximum items per page is 50.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
