<?php

namespace App\Http\Requests\achievement;

use App\Rules\OutOfDate;
use Illuminate\Foundation\Http\FormRequest;

class AchievementStoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:2', 'max:40'],
            'subtitle' => ['max:25'],
            'description' => ['required', 'string', 'min:10', 'max:300'],
            'date' => ['required', 'date', new OutOfDate],
            'user_id' => ['required']
        ];
    }
}
