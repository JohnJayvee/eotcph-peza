<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class ApplicationRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		
		$rules = [
			'name' => "required|unique:application,name,{$id}",
			'department_id' => "required",
			'pre_processing_code' => "required",
			'pre_processing_description' => "required",
			'pre_processing_cost' => "required",
			'post_processing_code' => "required",
			'post_processing_description' => "required",
			'post_processing_cost' => "required",
			'requirements_id' => "required"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'numeric' => "Please input a valid amount.",
			'min' => "Minimum amount is 0.",
			'integer' => "Invalid data. Please provide a valid input.",
		];
	}
}