@extends('master')

@title('Crush Party - Rice Program Council')

@section('content')
  <div class="logo">
      <img src="{{{ URL::asset('img/crush.png') }}}"
         width="200px" />
    </div>
  <div class="header">Rice Program Council: Crush Party</div>

  <div class="text justify">In order to proceed, you must be a Rice University student.</div>

  <div class="text justify">
    <a href="{{{ URL::to('AuthController@login') }}}" class="form_submit_style">Sign In</a>
  </div>
@endsection
