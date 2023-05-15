<?php

namespace App\Http\Requests\Inquiry;

use App\Enums\InquiryDocsCollectionEnum;
use App\Enums\RolesEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UploadDocRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'doc_type' => [
                'required',
                Rule::in(InquiryDocsCollectionEnum::values()),
                function ($attribute, $value, $fail) {
                    if (
                        $value == InquiryDocsCollectionEnum::CONFIDENTIAL->value &&
                        Auth::user()->getRoleNames()->first() == RolesEnum::CLIENT->value
                    ) {
                        $fail('This action is unauthorized.');
                    }
                }
            ],
            'docs' => ['required', 'array', 'min:1'],
            'docs.*' => ['mimes:pdf']
        ];
    }
}
