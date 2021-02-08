<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ApplicationRequirementsRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;

		$rules = [
			'name' => "required|unique:application_requirements,name,{$id}",
			'is_required' => "required"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
		];
	}
}