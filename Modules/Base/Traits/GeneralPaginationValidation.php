<?php

namespace Modules\Base\Traits;

trait GeneralPaginationValidation
{
    /**
     * Automatically adds validation logic in the withValidator method.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->filled('per_page') && !$this->filled('with_pagination')) {
                $validator->errors()->add('with_pagination', 'The with_pagination field is required when per_page is provided.');
            }
        });
    }
}
