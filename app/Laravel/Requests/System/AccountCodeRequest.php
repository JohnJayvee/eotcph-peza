<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class AccountCodeRequest extends RequestManager{

	public function rules(){

		$rules = [
			'code' => "required",
			'description' => "required",
			'alias' => "required",
			'default_cost' => "required",
			'ngas_code' => "required",
			'assigned_to_unit' => "required",

		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}