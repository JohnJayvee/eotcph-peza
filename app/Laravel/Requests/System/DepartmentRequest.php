<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class DepartmentRequest extends RequestManager{

	public function rules(){

		$rules = [
			'name' => "required|unique:department,name,NULL,id,deleted_at,NULL"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'name.unique'	=> "The Department name is already exist.",
		];
	}
}