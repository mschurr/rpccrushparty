@extends('master')

@title(SurveyConstants::$title)

@section('content')

	<script type="text/javascript">
		$(document).ready(function(){
			$("form").submit(function(event){
				if($(this).data('submitted') == true)
					event.preventDefault();
	
				$(this).data('submitted', true);
				$(this).find('input[type=submit]').prop('disabled', true);
			});
		});
	</script>

	<form action="/submit" method="POST">
		<div class="logo">
			<img src="{{{ URL::asset('img/crush.png') }}}"
				 width="200px" />
		</div>
	
		<div class="header">{{{$title}}}</div>

		<div class="text justify">Rice Program Council invites you to join your fellow Rice students at the annual Crush Party event on {{{$eventDate}}} at Willy's Pub. Answer the questions below and we will provide you with your best and worst matches among all participants. The survey will be available until {{{$expDate}}}.</div>


		@if(sizeof($errors) > 0)
			<div class="error">Your submission was unsuccessful. Please correct the errors below and try again.</div>
		@endif

		{{--
		<div class="label">Student ID:</div>
		{{berror($errors,'student_id')}}
		<div class="input"><input type="text" name="student_id" value="{{{dflt('student_id')}}}" /></div>
		--}}

		<input type="hidden" name="student_id" value="0" />

		<div class="label">Net ID:</div>
		{{berror($errors,'net_id')}}
		<div class="input"><input type="text" name="net_id" value="{{{dflt('net_id')}}}" /></div>

		<div class="label">First Name:</div>
		{{berror($errors,'first_name')}}
		<div class="input"><input type="text" name="first_name" value="{{{dflt('first_name')}}}" /></div>

		<div class="label">Last Name:</div>
		{{berror($errors,'last_name')}}
		<div class="input"><input type="text" name="last_name" value="{{{dflt('last_name')}}}" /></div>

		<div class="label">Email Address:</div>
		{{berror($errors,'email_address')}}
		<div class="input"><input type="text" name="email_address" value="{{{dflt('email_address')}}}" /></div>


		<div class="label">Residential College:</div>
		{{berror($errors,'college')}}
		<div class="input"><select name="college">
			<option value="-1"></option>
			@foreach($colleges as $cid => $cname)
				<option value="{{{$cid}}}" {{selected('college',$cid)}}>
					{{$cname}}
				</option>
			@endforeach
		</select></div>

		<div class="label">Year:</div>
		{{berror($errors,'year')}}
		<div class="input"><select name="year">
			<option value="-1"></option>
			@foreach($years as $cid => $cname)
				<option value="{{{$cid}}}" {{selected('year',$cid)}}>
					{{$cname}}
				</option>
			@endforeach
		</select></div>

		<div class="label">Intended Major:</div>
		{{berror($errors,'major')}}
		<div class="input"><select name="major">
			<option value="-1"></option>
			@foreach($majors as $cid => $cname)
				<option value="{{{$cid}}}" {{selected('major',$cid)}}>
					{{$cname}}
				</option>
			@endforeach
		</select></div>

		<div class="label">Gender:</div>
		{{berror($errors,'gender')}}
		<div class="input"><select name="gender">
			<option value="-1"></option>
			@foreach($genders as $gid => $gname)
				<option value="{{{$gid}}}"  {{selected('gender',$gid)}}>
					{{{$gname}}}
				</option>
			@endforeach
		</select></div>

		<div class="label">Gender(s) Interested In:</div>
		{{berror($errors,'interested_*')}}
		<div class="text">You will be matched with people of the gender(s) you indicate who also express an interest in your gender.</div>
		@foreach($genders as $gid => $gname)
			<div class="input"><label>
				<input type="checkbox" name="interested_{{{$gid}}}"  {{checked('interested_'.$gid)}} /> {{{$gname}}}
			</label></div>
		@endforeach

		@foreach($questions as $qid => $qdata)
			<div class="label">Question #{{{$qid+1}}}</div>
			{{berror($errors,'question_'.$qid)}}
			<div class="text">{{$qdata['text']}}</div>
			@foreach($qdata['options'] as $oid => $otext)
				<div class="input"><label>
					<input type="radio" name="question_{{{$qid}}}" value="{{{$oid}}}" {{checked('question_'.$qid,$oid)}} />
					{{{ $otext }}}
				</label></div>
			@endforeach
		@endforeach

		<div class="label">Verification</div>
		{{berror($errors,'captcha')}}
		<div class="text">To verify that you are human, please copy the text in the image to the field below.</div>
		<div class="input">
			{{ $captcha->embed() }}
		</div>

		<div class="label">Additional Options</div>
		<div class="input"><label>
			<input type="checkbox" name="send_results" {{checked('send_results')}} />
			I would like to be emailed a copy of my matches following the event.
		</label></div>
		<div class="input"><input type="submit" value="Submit!" /></div>

	</form>
@endsection