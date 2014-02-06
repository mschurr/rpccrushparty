<?php

import('Mail');
import('InlineCSS');

class SurveyMailer
{
	protected $matcher;
	protected $participants;

	public function __construct() {
		$this->matcher = new SurveyMatcher();
		$this->participants = new SurveyParticipantIterator();
	}

	public function send() {
		$i = 0;
		foreach($this->participants as $p) {
			if($i % 100)
				echo 'SENDING '.$i.'/'.sizeof($this->participants).'<br />';
			$this->sendForParticipant($p);
			$i++;
		}

		echo 'DONE';
	}

	public function sendForParticipant($p)
	{
		if($p['send_results'] != 1)
			return; // Honor the user's preference.

		$message = new Mail();
		$message->recipientName = $p['first_name'].' '.$p['last_name'];
		$message->recipient = $p['email_address'];
		$message->subject = 'Crush Party '.date('Y').' Results';

		$converter = new CssToInlineStyles();

		ob_start();
		$this->matcher->printMatches($p);
		$html = ob_get_contents();				
		ob_end_clean();

		$converter->setHTML($html);

		$css = File::open(FILE_ROOT.'/static/css/master.css')->content;
		$converter->setCSS($css);

		$message->body = $converter->convert();

		//echo $message->body;
		Mail::enqueue($message);
	}
}


