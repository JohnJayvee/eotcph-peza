<?php namespace App\Laravel\Requests\System;

use Session,Auth;
use App\Laravel\Requests\RequestManager;

class FileRequest extends RequestManager{

	public function rules(){
		
		foreach(range(1,count($this->get('name'))) as $index => $value){
          $rules["document.{$index}"] = "required|mimes:png,jpg,jpeg,pdf,xlsx|max:8192";
          $rules["name.{$index}"] = "required";
        }

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'confirmed' => "Password mismatch.",
			'max' => "Maximum file size to upload is 8MB (8192 KB)."
		];
	}
}