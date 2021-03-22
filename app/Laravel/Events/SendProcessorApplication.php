<?php 
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon;

class SendProcessorApplication extends Event {


	use SerializesModels;

	/**
	 * Create a new event instance.
	 *
	 * @return void
	 */
	public function __construct(array $form_data)
	{
		$this->data = $form_data;
		// $this->email = $form_data['insert'];

	

	}

	public function job(){	
		
		foreach($this->data as $index =>$value){
			$mailname = "Application Details";
			$user_email = $value['email'];
			$ref_code = $value['ref_code'];

			$this->data['full_name'] = $value['full_name'];
			$this->data['company_name'] = $value['company_name'];
			$this->data['application_name'] = $value['application_name'];
			$this->data['department_name'] = $value['department_name'];
			$this->data['ref_code'] = $value['ref_code'];
			$this->data['created_at'] = $value['created_at'];
			

			Mail::send('emails.processor-application', $this->data, function($message) use ($mailname,$user_email,$ref_code){
				$message->from('eotcph-noreply@ziaplex.biz');
				$message->to($user_email);
				$message->subject("New Online Application Submission. Reference Code: ".$ref_code);
			});
		}


		
		
		
	}
}
