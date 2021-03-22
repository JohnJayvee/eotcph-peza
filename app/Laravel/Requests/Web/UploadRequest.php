<?php namespace App\Laravel\Requests\Web;

use Session,Auth;
use App\Laravel\Requests\RequestManager;
use App\Laravel\Models\ApplicationRequirements;

class UploadRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		

		$required = ApplicationRequirements::whereIn('id',$this->get('requirements_id'))->get();

		foreach ($required as $key => $value) {
			$rules['file'.$value->id] = "required|mimes:pdf,docx,doc|max:8192";
		}

		return $rules;
	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'file.required'	=> "No File Uploaded.",
			'file.*' => 'Only PDF File are allowed.',
			'mimes' => 'The file Failed to upload.'
			'max' => "Maximum file size to upload is 8MB (8192 KB)."
		];
	}
}