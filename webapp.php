<?php

import('SurveySystem');
import('BladeExtensions');
import('SQLExtensions');

Config::set(array(
	'database.driver' => 'mysql',
	'database.user'   => 'httpd',
	'database.pass'   => 'httpd',
	'database.name'   => 'crushparty'
));

Route::get( '/',		'MainController@form'	);
Route::post('/submit',	'MainController@submit'	);
Route::get( '/error', 	'MainController@error'	);
Route::get( '/thanks', 	'MainController@thanks'	);
Route::get( '/results', 'MainController@results');
Route::get( '/seed', 	'MainController@seed'	);

class MainController extends Controller
{
	public function form($errors=array())
	{
		return View::make('survey')->with(array(
			'genders'   => SurveyConstants::$genders,
			'colleges'  => SurveyConstants::$colleges,
			'questions' => SurveyConstants::$questions,
			'years'     => SurveyConstants::$years,
			'title'		=> SurveyConstants::$title,
			'eventDate' => SurveyConstants::$eventDate,
			'expDate'	=> SurveyConstants::$expDate,
			'errors'    => $errors
		));
	}

	public function _validate()
	{
		$errors = array();
		$required = array('first_name', 'last_name', 'net_id', 'student_id', 'email_address');

		// Required Text Fields
		foreach($required as $k => $field) {
			if(!isset($this->request->post[$field]) ||
				strlen($this->request->post[$field]) < 1 ||
				strlen($this->request->post[$field]) > 100)
				$errors[$field] = 'This field is required.';
		}

		// Year
		if(!isset($this->request->post['year']) ||
		   !is_numeric($this->request->post['year']) ||
		   !isset(SurveyConstants::$years[$this->request->post['year']])) {
			$errors['year'] = 'You must select a valid option.';
		}

		// College
		if(!isset($this->request->post['college']) ||
		   !is_numeric($this->request->post['college']) ||
		   !isset(SurveyConstants::$colleges[$this->request->post['college']])) {
			$errors['college'] = 'You must select a valid option.';
		}

		// Gender
		if(!isset($this->request->post['gender']) ||
		   !is_numeric($this->request->post['gender']) ||
		   !isset(SurveyConstants::$genders[$this->request->post['gender']])) {
			$errors['gender'] = 'You must select a valid option.';
		}

		// Interested
		$selected = false;
		for($i = 0; $i < sizeof(SurveyConstants::$genders); $i++)
			if(isset($this->request->post['interested_'.$i]))
				$selected = true;
		if(!$selected) $errors['interested_*'] = 'You must select at least one option.';

		// Questions
		for($i = 0; $i < sizeof(SurveyConstants::$questions); $i++) {
			// Check that the question is set.
			if(!isset($this->request->post['question_'.$i])) {
				$errors['question_'.$i] = 'This field is required.';
				continue;
			}

			// Check that the option is valid.
			if(!is_numeric($this->request->post['question_'.$i]) || !isset(SurveyConstants::$questions[$i]['options'][$this->request->post['question_'.$i]])) {
				$errors['question_'.$i] = 'You must select a valid option.';
				continue;
			}
		}

		return $errors;
	}

	public function submit()
	{
		$errors = $this->_validate();

		if(sizeof($errors) > 0)
			return $this->form($errors);

		try {
			$fields = SurveyConstants::fields();
			$stmt = $this->db->prepare("INSERT INTO `surveys` (".sql_keys($fields).") VALUES (".sql_values($fields).");");
			
			$data = array(
				':first_name' => $this->request->post['first_name'],
				':last_name' => $this->request->post['last_name'],
				':net_id' => $this->request->post['net_id'],
				':student_id' => $this->request->post['student_id'],
				':college' => $this->request->post['college'],
				':year' => $this->request->post['year'],
				':email_address' => $this->request->post['email_address'],
				':gender' => $this->request->post['gender'],
				':send_results' => isset($this->request->post['send_results']) ? 1 : 0
			);

			for($i = 0; $i < sizeof(SurveyConstants::$genders); $i++)
				$data[':interested_'.$i] = isset($this->request->post['interested_'.$i]) ? 1 : 0;

			for($i = 0; $i < sizeof(SurveyConstants::$questions); $i++)
				$data[':question_'.$i] = $this->request->post['question_'.$i];

			$q = $stmt->execute($data);

			return Redirect::to('/thanks');
		}
		catch (DBException $dbe) {
			return Redirect::to('/error');
		}
	}

	public function thanks()
	{
		return View::make('thanks');
	}

	public function error()
	{
		return View::make('error');
	}

	public function results()
	{
		if($this->request->server['REMOTE_ADDR'] != '127.0.0.1' && $this->request->server['REMOTE_ADDR'] != '::1')
			return 403;

		$matcher = new SurveyMatcher();
		$matcher->match();
	}

	public function seed()
	{
		if($this->request->server['REMOTE_ADDR'] != '127.0.0.1' && $this->request->server['REMOTE_ADDR'] != '::1')
			return 403;

		$matcher = new SurveyMatcher();
		$matcher->seed();

		$this->response->write('OK');
	}
}