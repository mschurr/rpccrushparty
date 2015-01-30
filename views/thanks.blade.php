@extends('master')

@title('Thank You!')

@section('content')
	<div class="header">Thank you!</div>
	<div class="message">Your submission has been recorded and you are now registered for the event. If you wish to alter your submission, you may <a href="{{{ URL::to('MainController@form') }}}">submit again</a> at any time before the survey closes.</div>
  <div class="message">After the conclusion of the event, you may view this website again to see the results of your survey online (if you are unable to pick up the physical copy).</div>
@endsection
