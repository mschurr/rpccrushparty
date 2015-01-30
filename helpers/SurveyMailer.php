<?php

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
		ini_set('max_execution_time', 1000);
		$i = 0;
		foreach($this->participants as $p) {
			//if($i % 100)
			//	echo 'SENDING '.$i.'/'.sizeof($this->participants).'<br />';
			$r = $this->sendForParticipant($p);
			usleep(100000); // 0.1 sec

			if($r === true) {
				$i++;
			}

			//if($i > 10)
			//	die();
		}

		echo 'DONE';
	}

	public function sendForParticipant($p)
	{
		if($p['send_results'] != 1)
			return false; // Honor the user's preference.

		if($p['results_sent'] == 1) {
			echo 'SKIP '.$p['id'].'-'.$p['email_address'];
			return false; // Results already sent, skip.
		}

		$message = new Mail();
		$message->recipientName = $p['first_name'].' '.$p['last_name'];
		$message->recipient = $p['email_address'];
		$message->subject = 'Crush Party '.date('Y').' Results';

		$converter = new CssToInlineStyles();

		// BUILD: Matches For Gender
		ob_start();
		$this->matcher->printMatches($p);
		$html = ob_get_contents();
		ob_end_clean();
		$css = file_get_contents(FILE_ROOT.'/static/css/master.css');
		$converter->setHTML($html);
		$converter->setCSS($css);
		$matches_real = $converter->convert();

		// BUILD: Matches For Everyone
		ob_start();
		$this->matcher->printMatches($p,true);
		$html = ob_get_contents();
		ob_end_clean();
		$css = file_get_contents(FILE_ROOT.'/static/css/master.css');
		$converter->setHTML($html);
		$converter->setCSS($css);
		$matches_everyone = $converter->convert();

		$message->body = '
		<html>
			<body style="padding: 20px">
				Hello '.escape_html($p['first_name']).' '.escape_html($p['last_name']).',<br />
				<br />
				At the bottom of this email, you will find a copy of the matches that were handed out at the Crush Party event. For additional fun, I have also
				included a second set of matches that ranks all participants.<br />
				<br />
				Best,<br />
				<br />
				<strong>Matthew Schurr</strong><br />
				Webmaster, Duncan College<br />
				Computer Science, Rice University 16<br />
				<br />
				<br />
				<span style="font-size: 20px; font-weight: bold;">Your Real Results</span><br />
				These are the results that were handed out at the offical event last week. These results include only people of the gender that you expressed interest in who also expressed an interest in your gender.<br />
				<br />
				'.$matches_real.'<br />
				<br />
				<span style="font-size: 20px; font-weight: bold;">Your Expanded Results</span><br />
				These results include your best matches among everyone who participated in the survey regardless of gender preference compatability.<br />
				<br />
				'.$matches_everyone.'<br />
			</body>
		</html>
		';

		//echo $message->body;

		try {
			Mail::enqueue($message);
			echo 'SENT '.$p['id'].'-'.$p['email_address'];
			$db = App::getDatabase();
			$stmt = $db->prepare("UPDATE `surveys` SET `results_sent` = '1' WHERE `id` = ?;");
			$stmt->execute($p['id']);
		}
		catch(MailException $e) {
			echo 'FAIL '.$p['id'].'-'.$p['email_address'];
		}
		return true;
	}
}


