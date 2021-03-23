<?php namespace App\Laravel\Requests\Web;

use Session,Auth;
use App\Laravel\Requests\RequestManager;
use App\Laravel\Models\ApplicationRequirements;

class FileUploadRequest extends RequestManager{

	public function rules(){

		

		$required = ApplicationRequirements::whereIn('id',$this->get('requirements_id'))->get();

		foreach ($required as $key => $value) {
			$rules['file'.$value->id] = "required|mimes:pdf,docx,doc|max:8192";
		}

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'contact_number.phone' => "Please provide a valid PH mobile number.",
			'password_format' => "Password must be 6-20 alphanumeric and some allowed special characters only.",
			'max' => "Maximum file size to upload is 8MB (8192 KB).",
		];
	}
}