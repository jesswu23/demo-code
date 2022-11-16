<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveStoreRequest extends FormRequest
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
			'start_date' => 'required',
			'start_time' => 'required',
			'end_date' => 'required',
			'end_time' => 'required',
			'type' => 'required',
			'reason' => 'required',
		];
	}

	/**
	 * Custom error message
	 * @return array
	 */
	public function messages()
	{
		return [
			'start_date.required' => 'Start date can not null. ',
			'start_time.required' => 'Start time can not null. ',
			'end_date.required' => 'End date can not null. ',
			'end_time.required' => 'End time can not null. ',
			'type.required' => 'Type can not null. ',
			'reason.required' => 'Reason can not null. '
		];
	}
}
