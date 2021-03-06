<?php namespace App\Laravel\Requests\Web;

use Session,Auth;
use App\Laravel\Requests\RequestManager;
use App\Laravel\Models\ApplicationRequirements;

class TransactionRequest extends RequestManager{

	public function rules(){

		$id = $this->route('id')?:0;
		$file = $this->file('file') ? count($this->file('file')) : 0;

		$rules = [
			'full_name' => "required",
			'company_name' => "required",
			'application_id' => "required",
			'email' => "required",
			'contact_number' => "required",
			'department_id' => "required",
			'processing_fee' => "required",
			//'partial_amount' => "nullable|minimum_amount:application_id,partial_amount",
			'zone_id' => "required",
			'contact_number' => "required|max:10|phone:PH",
    		'file.*' => 'required|mimes:pdf,docx,doc|max:8192',
            'notes' => ['nullable', 'string'],
		];

		$required = ApplicationRequirements::whereIn('id',explode(",", $this->get('requirements_id')))->where('is_required',"yes")->get();

		foreach ($required as $key => $value) {
			$rules['file'.$value->id] = "required|mimes:pdf,docx,doc|max:5000";
		}


		return $rules;

	}

	public function messages(){
		return [
			'required'	=> "Field is required.",
			'partial_amount.minimum_amount' => ":message",
			'contact_number.phone' => "Please provide a valid PH mobile number.",
			'file.required'	=> "No File Uploaded.",
			'file.*' => 'Only PDF File are allowed.',
			'file_count.with_count' => 'Please Submit minimum requirements.',
			'mimes' => 'The file Failed to upload.',
			'max' => "Maximum file size to upload is 8MB (8192 KB).",
		];

	}
}
