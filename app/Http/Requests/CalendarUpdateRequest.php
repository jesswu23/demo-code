<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarUpdateRequest extends FormRequest
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
			'is_holiday' => 'required'
		];
	}

	/**
	 * Custom error message
	 * @return array
	 */
	public function messages()
	{
		return [
			'is_holiday.required' => 'Holiday can not null. '
		];
	}
}
