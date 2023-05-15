<?php

namespace App\Http\Requests\Payment;

use App\Enums\BalanceSheetCategoryEnum;
use App\Enums\PaymentTabsEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class StorePaymentRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        abort_if(
            !in_array(
                $this->segment(5),
                PaymentTabsEnum::values()
            ),
            Response::HTTP_NOT_FOUND,
            "Invalid tab name: {$this->segment(5)}"
        );

        abort_if(
            $this->segment(5) === PaymentTabsEnum::PROFIT->value, // Profit tab just show some data
            Response::HTTP_NOT_ACCEPTABLE
        );
    }

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
        $defaultRules = [
            'description' => ['required', 'string', 'min:5'],
//            'debit' => ['required', 'numeric', 'min:0'],
//            'credit' => ['required', 'numeric', 'min:0'],
        ];

        $rules = [
            PaymentTabsEnum::BALANCE_SHEET->value => [
                'inquiry_id' => [
                    Rule::requiredIf($this->input('category') == BalanceSheetCategoryEnum::ORDER_RELATED->value),
                    Rule::exists('inquiries', 'id')
                ],
                'remark' => ['nullable', 'string', 'min:2'],
                'category' => ['required', 'string', Rule::in(BalanceSheetCategoryEnum::values())],
                'supplier_id' => ['required', Rule::exists('suppliers', 'id')],
                'customer_id' => ['nullable', Rule::exists('users', 'id')]
            ],
            PaymentTabsEnum::PAYMENTS->value => [
                'inquiry_id' => ['required', Rule::exists('inquiries', 'id')]
            ],
            PaymentTabsEnum::FUTURE_DUES->value => [
                'inquiry_id' => ['required', Rule::exists('inquiries', 'id')],
                'date' => ['required', 'date', 'date_format:Y-m-d'], // Payment date
                'is_paid' => ['required', 'boolean']
            ],
            PaymentTabsEnum::PETTY->value => [
                'invoice_no' => ['nullable', 'string', 'min:2'],
            ]
        ];

        return array_merge($defaultRules, $rules[$this->segment(5)]);
    }
}
