<?php

import('SurveySystem');
import('BladeExtensions');
import('BladeDefaulter');
import('Security');

require(FILE_ROOT.'/config.php');

/*############################
# Authentication Service
############################*/

Route::get ('/login',  'AuthController@login'      );
Route::post('/login',  'AuthController@loginAction');
Route::get ('/logout', 'AuthController@logout'     );

/*############################
# Application Pages
############################*/

$authFilter = function(Request $request){
  if(!$request->session->auth->loggedIn)
    return Redirect::to('AuthController@login');
  return true;
};

Route::get('/', 'MainController@Home');

Route::filter($authFilter, function() {
  Route::get( '/form',          'MainController@form'    );
  Route::any( '/submit',        'MainController@submit'  );
  Route::get( '/error',         'MainController@error'   );
  Route::get( '/thanks',        'MainController@thanks'  );
  Route::get( '/expired',       'MainController@expired' );
});

$cliFilter = function(Request $request) {
  if(!(php_sapi_name() == 'cli-server'))
    return 403;
  return true;
};

Route::filter($cliFilter, function() {
  Route::get( '/results',       'MainController@results' );
  Route::get( '/seed',          'MainController@seed'    );
  Route::get( '/result/{id}',   'MainController@result'  );
  Route::get( '/resulta/{id}',  'MainController@resulta' );
  Route::get( '/answers/{id}',  'MainController@answers' );
  Route::get( '/mail',          'MainController@mail'    );
});


class MainController extends Controller
{
  public function home()
  {
    if ($this->request->session->auth->loggedIn) {
      return Redirect::to('/form');
    }

    return View::make('home')->with([
      'eventDate' => SurveyConstants::$eventDate,
      'expDate'   => SurveyConstants::$expDate
    ]);
  }

  public function form($errors=array())
  {
    if(Config::get('crush.expired', false) === true)
      return Redirect::to('/expired');

    $previous = $this->db->prepare("SELECT * FROM `surveys` WHERE `net_id` = ? LIMIT 1;")
        ->execute([$this->user->username()]);
    $submitted = count($previous) > 0;

    $defaulter = null;
    if ($submitted && count($this->request->post) == 0) {
      $defaulter = new BladeDefaulter([$this->request->post, $previous->row]);
    } else {
      $defaulter = new BladeDefaulter([$this->request->post]);
    }

    return View::make('survey')->with(array(
      'submitted' => $submitted,
      'dflt'      => $defaulter,
      'genders'   => SurveyConstants::$genders,
      'colleges'  => SurveyConstants::$colleges,
      'questions' => SurveyConstants::$questions,
      'years'     => SurveyConstants::$years,
      'title'     => SurveyConstants::$title,
      'eventDate' => SurveyConstants::$eventDate,
      'expDate'   => SurveyConstants::$expDate,
      'majors'    => SurveyConstants::$majors,
      'errors'    => $errors,
      'net_id'    => $this->user->username(),
      'captcha'   => (new HumanDetector())
    ));
  }

  public function _validate()
  {
    $errors = array();
    $required = array('first_name', 'last_name', /*'net_id',*/ 'student_id', 'email_address');

    // CAPTCHA
    /*if(!with(new HumanDetector())->isHuman()) {
      $errors['captcha'] = 'The text you entered did not match the image.';
    }*/

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

    // Major
    if(!isset($this->request->post['major']) ||
       !is_numeric($this->request->post['major']) ||
       !isset(SurveyConstants::$majors[$this->request->post['major']])) {
      $errors['major'] = 'You must select a valid option.';
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
    if(Config::get('crush.expired', false) === true)
      return Redirect::to('/expired');

    $errors = $this->_validate();

    if(sizeof($errors) > 0)
      return $this->form($errors);

    try {
      $data = array(
        'first_name' => $this->request->post['first_name'],
        'last_name' => $this->request->post['last_name'],
        'net_id' => $this->user->username(),//$this->request->post['net_id'],
        'student_id' => $this->request->post['student_id'],
        'college' => $this->request->post['college'],
        'major' => $this->request->post['major'],
        'year' => $this->request->post['year'],
        'email_address' => $this->request->post['email_address'],
        'gender' => $this->request->post['gender'],
        'send_results' => isset($this->request->post['send_results']) ? 1 : 0
      );

      for($i = 0; $i < sizeof(SurveyConstants::$genders); $i++)
        $data['interested_'.$i] = isset($this->request->post['interested_'.$i]) ? 1 : 0;

      for($i = 0; $i < sizeof(SurveyConstants::$questions); $i++)
        $data['question_'.$i] = $this->request->post['question_'.$i];


      $stmt = $this->db->prepare("INSERT INTO `surveys` (".sql_keys($data).") VALUES (".sql_values($data).");");
      $q = $stmt->execute(sql_parameters($data));

      $this->db->prepare("DELETE FROM `surveys` WHERE `net_id` = ? AND `id` != ?;")
          ->execute([$this->user->username(), $q->insertId]);

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

  public function expired()
  {
    if (Config::get('crush.publish', false)) {
      $html = Cache::section('surveys')->remember($this->user->username(), function() {
        import('InlineCSS');
        $converter = new CssToInlineStyles();
        $m = new SurveyMatcher();
        $p = $m->getParticipantByNetId($this->user->username());

        if(sizeof($p) == 0) {
          return '';
        }

        ob_start();
        $m->printMatches($p);
        $html = ob_get_contents();
        ob_end_clean();
        $css = file_get_contents(FILE_ROOT.'/static/css/master.css');
        $converter->setHTML($html);
        $converter->setCSS($css);
        $matches_real = $converter->convert();
        return $matches_real;
      });

      if ($html !== '') {
        echo '<link rel="stylesheet" type="text/css" href="'.URL::asset('css/master.css').'" />';
        echo '<a href="'.URL::to('AuthController@logout').'" style="float:right">Log Out</a>';
        echo $html;
        return;
      }
    }

    return View::make('expired');
  }

  /**
   * Returns the results of all users (optionally limited).
   */
  public function results()
  {
    if(!(php_sapi_name() == 'cli-server'))
      return 403;

    if($this->request->server['REMOTE_ADDR'] != '127.0.0.1' && $this->request->server['REMOTE_ADDR'] != '::1')
      return 403;

    $matcher = new SurveyMatcher();
    $matcher->match((isset($this->request->get['limit']) ? $this->request->get['limit'] : null));
  }

  /**
   * Seeds the database with random records for testing.
   */
  public function seed()
  {
    if(!(php_sapi_name() == 'cli-server'))
      return 403;

    if($this->request->server['REMOTE_ADDR'] != '127.0.0.1' && $this->request->server['REMOTE_ADDR'] != '::1')
      return 403;

    $matcher = new SurveyMatcher();
    $matcher->seed((isset($this->request->get['limit']) ? $this->request->get['limit'] : 150));

    $this->response->write('OK');
  }

  /**
   * Returns the results for a given user by their id.
   */
  public function result($id)
  {
    if(!(php_sapi_name() == 'cli-server'))
      return 403;

    $m = new SurveyMatcher();
    $p = $m->getParticipantById($id);

    if(sizeof($p) == 0) return 404;
    echo '<link rel="stylesheet" type="text/css" href="'.URL::asset('css/master.css').'" />';
    $m->printMatches($p);
  }

  /**
   * Returns the answers for a given user id.
   * Useful for experimenting with match scoring algorithm with randomly generated records.
   */
  public function answers($id)
  {
    if(!(php_sapi_name() == 'cli-server'))
      return 403;

    $m = new SurveyMatcher();
    $p = $m->getParticipantById($id);

    if(sizeof($p) == 0) return 404;

    $ig = array('interested_0', 'interested_1', 'send_results');
    foreach($p as $k => $v) {
      if(in_array($k, $ig) && $v == 0)
        continue;
      $this->request->post[$k] = $v;
    }

    return $this->form(array('*' => 'This survey is read-only.'));
  }

  /**
   * Returns the matches for a given user id, ignoring feasibility constraints.
   */
  public function resulta($id)
  {
    if(!(php_sapi_name() == 'cli-server'))
      return 403;

    $m = new SurveyMatcher();
    $p = $m->getParticipantById($id);

    if(sizeof($p) == 0) return 404;
    echo '<link rel="stylesheet" type="text/css" href="'.URL::asset('css/master.css').'" />';
    $m->printMatches($p,true);
  }

  /**
   * Performs the mailing of results.
   */
  public function mail()
  {
    if(!(php_sapi_name() == 'cli-server'))
      return 403;

    import('SurveyMailer');

    $mailer = new SurveyMailer();
    $mailer->send();
    //$mailer->sendForParticipant(with(new SurveyMatcher())->getParticipantById(1));
  }
}
