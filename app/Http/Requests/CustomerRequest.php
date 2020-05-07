<?php

namespace App\Http\Requests;

use App\Repositories\CustomerRepository;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * CustomerRepository
     *
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * CustomerRepository
     *
     * @param CustomerRepository $customerRepository
     *
     * @return bool
     */
    public function authorize(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $basicValidation = [
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'email' => ['required', 'email', 'min:4', 'unique:customers,email']
        ];

        /**
         * It will replace the email validation if its a put request
         */
        if ($this->isMethod('put')) {
            $basicValidation['email'] = [
                'required',
                'min:4',
                'email',
                Rule::unique('customers', 'email')->ignore(
                    $this->customerRepository->find($this->customer)->id,
                )
            ];
        }

        return $basicValidation;
    }
}
