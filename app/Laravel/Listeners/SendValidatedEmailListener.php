<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendValidatedEmailReference;

class SendValidatedEmailListener{

	public function handle(SendValidatedEmailReference $email){
		$email->job();
	}
}