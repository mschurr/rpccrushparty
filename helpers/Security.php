<?php
/**
 * Limits the number of actions of a particular type that a client can perform within a time interval.
 */
class RateLimiter
{
	protected /*string*/ $identifier;
	protected /*string*/ $group;
	protected /*int*/ $expire;
	protected /*int*/ $limit;
	protected /*Database*/ $db;

	public /*void*/ function __construct(/*string*/ $identifier, /*string*/ $group, /*int*/ $expire=600, /*int*/ $limit=5) {
		$this->identifier = $identifier;
		$this->group = $group;
		$this->expire = $expire;
		$this->limit = $limit;
		$this->db = App::getDatabase();
	}

	public /*void*/ function update() {
		$stmt = $this->db->prepare("INSERT INTO `sec_rate_limit` (`id`, `group`, `time`) VALUES (?, ?, ?);");
		$q = $stmt->execute(array(
			$this->identifier,
			$this->group,
			time()
		));
	}

	public function isAllowed() {
		$stmt = $this->db->prepare("SELECT COUNT(*) as `counter` FROM `sec_rate_limit` WHERE `id` = ? AND `group` = ? AND `time` > ?;");
		$q = $stmt->execute(array(
			$this->identifier,
			$this->group,
			time() - $this->expire
		));

		return ($q->row['counter'] <= $limit);
	}
}

/**
 * Can be embedded in forms to detect whether or not the user is human. Useful for preventing spam and/or mass submissions.
 */
class HumanDetector
{
	protected /*string*/ $error = null;

	public /*void*/ function __construct() {
	}

	public /*string*/ function embed() {
		return recaptcha_get_html(
			Config::get('recaptcha.publicKey', function(){
				throw new Exception("You must configure [recaptcha.publicKey] to use recaptcha.");
			}),
			null, // Error
			true // Use SSL
		);
	}

	public /*bool*/ function isHuman() {
		$request = App::getRequest();
		$privateKey = Config::get('recaptcha.privateKey', function(){
			throw new Exception("You must configure [recaptcha.privateKey] to use recaptcha.");
		});

		if(!isset($request->post['recaptcha_challenge_field']) || !isset($request->post['recaptcha_response_field'])) {
			$this->error = 'The captcha was not filled out.';
			return false;
		}

		$response = recaptcha_check_answer(
			$privateKey,
			$request->ip,
			$request->post['recaptcha_challenge_field'],
			$request->post['recaptcha_response_field']
		);

		$this->error = $response->error;
		return $response->is_valid;
	}

	public /*string*/ function error()	{
		return $this->error;
	}
}
