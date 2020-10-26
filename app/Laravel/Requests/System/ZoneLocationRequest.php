<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ZoneLocationRequest extends RequestManager{

	public function rules(){

		$rules = [
			'code' => "required",
			'ecozone' => "required",
			'type' => "required",
			'nature' => "required",
			'developer' => "required",
			'city' => "required",
			'province' => "required",
			'region' => "required",
			'region_code' => "required",
			'dev_comp_code' => "required",
			'obo_cluster' => "required",
			'income_cluster' => "required",
			'serial' => "required",

		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}