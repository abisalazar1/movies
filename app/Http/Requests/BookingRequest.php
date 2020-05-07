<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\Sevices\Authorisation\BookingAuthorisationService;

class BookingRequest extends FormRequest
{
    /**
     * authorisation 0
     *
     * @param BookingAuthorisationService $bookingAuthorisationService
     *
     * @return bool
     */
    public function authorize(BookingAuthorisationService $bookingAuthorisationService)
    {
        if ($this->isMethod('post')) {
            $bookingAuthorisationService->setMovieTimeById($this->movie_time_id)->checkSeats($this->seats);
        } else {
            $bookingAuthorisationService->setBookingById($this->booking)->canUpdateSeats($this->seats);
        }


        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $basicRules = [
            'customer' => ['required', 'integer', Rule::exists('customers', 'id')],
            'seats' => ['nullable', 'integer', 'min:1', 'max:10']
        ];
        
        if ($this->isMethod('put')) {
            $basicRules['booking'] = ['required', 'integer', Rule::exists('bookings', 'id')->where(function ($query) {
                $query->where('customer_id', $this->customer);
            })];
        } else {
            $basicRules['movie_time_id'] = ['required', 'integer', Rule::exists('movie_times', 'id')];
        }

        return $basicRules;
    }

    /**
     * Data to be validated
     *
     * @return array
     */
    public function validationData()
    {
        $dataToValidate = array_merge(['customer' => $this->customer], $this->all());

        if ($this->isMethod('put')) {
            $dataToValidate['booking'] = $this->booking;
        }

        return $dataToValidate;
    }
}
