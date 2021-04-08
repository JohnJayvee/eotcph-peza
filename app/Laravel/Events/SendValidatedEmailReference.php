<?php
namespace App\Laravel\Events;

use Illuminate\Queue\SerializesModels;
use Mail,Str,Helper,Carbon;

class SendValidatedEmailReference extends Event {


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
			$ref_num = $value['ref_num'];

			$this->data['full_name'] = $value['full_name'];
			$this->data['application_name'] = $value['application_name'];
			$this->data['department_name'] = $value['department_name'];
			$this->data['ref_num'] = $value['ref_num'];
			$this->data['modified_at'] = $value['modified_at'];
			$this->data['amount'] = $value['amount'];
            $this->data['notes'] = $value['notes'];
            $this->date['remarks'] = $value['remarks'];

			Mail::send('emails.application-validated', $this->data, function($message) use ($mailname,$user_email,$ref_num){
				$message->from('eotcph-noreply@ziaplex.biz');
				$message->to($user_email);
				$message->subject("Your application has been approved. Please prepare payment. Reference Code: ".$ref_num);
			});
		}





	}
}
