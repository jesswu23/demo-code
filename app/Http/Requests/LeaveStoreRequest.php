<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Enums\LeaveType;

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
			'start_date'	=> 'required|regex:/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}\z/i',
			'start_time'	=> 'required',
			'end_date'		=> 'required|regex:/^[0-9]{4}-[0-9]{1,2}-[0-9]{1,2}\z/i',
			'end_time'		=> 'required',
			'type'			=> 'required|integer|numeric|enum_value:' . LeaveType::class,
			'reason'		=> 'required'
		];
	}

	/**
	 * Custom error message
	 * @return array
	 */
	public function messages()
	{
		return [
			'start_date.required'	=> 'Start date can not null.',
			'start_date.regex'		=> 'Start date format must be yyyy-mm-dd.',
			'start_time.required'	=> 'Start time can not null.',
			'end_date.required'		=> 'End date can not null.',
			'end_date.regex'		=> 'End date format must be yyyy-mm-dd.',
			'end_time.required'		=> 'End time can not null.',
			'type.required'			=> 'Type can not null.',
			'type.integer'			=> 'Type must be integer.',
			'type.numeric'			=> 'Type must be number.',
			'type.enum_value'		=> 'Type is undefined.',
			'reason.required'		=> 'Reason can not null.'
		];
	}
}
