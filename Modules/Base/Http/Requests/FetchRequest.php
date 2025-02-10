<?php

namespace Modules\Base\Http\Requests;
use Illuminate\Validation\Rules\Enum;
use Modules\Base\Enum\ActiveEnum;
use Modules\Base\Traits\GeneralPaginationValidation;

class FetchRequest extends ApiRequest
{
    use GeneralPaginationValidation ;
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "with_pagination" => ["nullable" , "in:1,0"],
            'is_active' => ['nullable', new Enum(ActiveEnum::class)],
            "search" => ["nullable","string"],
            "per_page" => ["nullable","integer"],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
