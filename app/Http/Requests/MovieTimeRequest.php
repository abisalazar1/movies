<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class MovieTimeRequest extends FormRequest
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
        $basicRules =  [
            'movie' => ['required', Rule::exists('movies', 'id')],
            'date_time' => ['required', 'date_format:Y-m-d H:i']
        ];

        if ($this->isMethod('put')) {
            $basicRules['time'] = ['required', Rule::exists('movie_times', 'id')->where(function ($query) {
                $query->where('movie_id', $this->movie);
            })];
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
        $dataToValidate = array_merge(['movie' => $this->movie], $this->all());

        if ($this->isMethod('put')) {
            $dataToValidate['time'] = $this->time;
        }

        return $dataToValidate;
    }
}
