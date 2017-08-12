<?php

namespace App\Http\Requests;

use App\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreInvoice extends FormRequest
{
    use RequestMutators;

    protected $mutator_options = [
        'group_inputs' => [
            'items' => [
                'description', 'quantity', 'cost'
            ]
        ],
        'truncate_empty_groups' => true
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->is_admin;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_issued' => 'required|date',
            'client_id' => 'required|integer|exists:invoice_clients,id',
            'description' => 'required|array',
            'quantity' => 'required|array',
            'cost' => 'required|array'
        ];
    }
}
