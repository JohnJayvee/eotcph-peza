<?php 
namespace App\Laravel\Listeners;

use App\Laravel\Events\SendPreProcessEmail;

class SendPreProcessListener{

	public function handle(SendPreProcessEmail $email){
		$email->job();

	}
}