<?php

namespace Modules\General\app\Http\Requests;


class FetchRequest
{
    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:255'],
            'filters' => ['nullable', 'array'],
            'filters.*' => ['nullable','string'],
            'with_pagination' => ['nullable', 'boolean'],
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'sort_by' => ['nullable', 'string', 'max:50'],
            'sort_direction' => ['nullable', 'in:asc,desc']
        ];
    }

    public function messages(): array
    {
        return [
            'search.string' => 'Search must be a string.',
            'per_page.integer' => 'Per page must be a valid number.',
            'sort_direction.in' => 'Sort direction must be "asc" or "desc".'
        ];
    }
}

