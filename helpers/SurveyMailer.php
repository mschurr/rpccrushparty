<?php

import('Mail');

class SurveyMailer
{
	protected $matcher;
	protected $participants;

	public function __construct() {
		$this->matcher = new SurveyMatcher();
		$this->participants = new SurveyParticipantIterator();
	}

	public function send() {
		foreach($this->participants as $p) {

			$matches = $this->matcher->matchesForParticipant($p);
			
			$body = print_r($matches, true);

			$message = new Mail();
			$message->recipientName = $p['first_name'].' '.$p['last_name'];
			$message->recipient = $p['email_address'];
			$message->subject = 'Crush Party '.date('Y').' Results';
			$message->body = $body;

			Mail::enqueue($message);
		}
	}
}


