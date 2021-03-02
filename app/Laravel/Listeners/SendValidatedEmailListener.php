<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendApprovedEmailReference;

class SendValidatedEmailListener{

	public function handle(SendValidatedEmailReference $email){
		$email->job();

	}
}