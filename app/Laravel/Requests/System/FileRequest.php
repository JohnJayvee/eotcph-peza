<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class FileRequest extends RequestManager{

	public function rules(){
		
		foreach(range(1,count($this->get('name'))) as $index => $value){
          $rules["document.{$index}"] = "required|mimes:png,jpg,jpeg,pdf,xlsx";
          $rules["name.{$index}"] = "required";
        }

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'confirmed' => "Password mismatch.",
		];
	}
}