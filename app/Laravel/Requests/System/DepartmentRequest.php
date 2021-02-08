<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class DepartmentRequest extends RequestManager{

	public function rules(){
		$id = $this->route('id')?:0;

		$rules = [
			'name' => "required|unique:department,name,{$id}"
		];

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'name.unique'	=> "The Unit name is already exist.",
		];
	}
}