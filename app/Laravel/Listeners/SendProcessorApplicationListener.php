<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendProcessorApplication;

class SendProcessorApplicationListener{

	public function handle(SendProcessorApplication $email){
		$email->job();

	}
}