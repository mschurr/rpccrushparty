<?php

function checked($input, $value=null) {
	if($value !== null) {
		return isset(App::getRequest()->post[$input]) && App::getRequest()->post[$input] == $value ? ' checked="checked"' : '';
	}
	return isset(App::getRequest()->post[$input]) ? ' checked="checked"' : '';
}

function selected($input, $value) {
	if(!isset(App::getRequest()->post[$input]))
		return 'qwer';

	if($value == App::getRequest()->post[$input])
		return ' selected="selected"';
}

function dflt($input, $dflt='') {
	if(!isset(App::getRequest()->post[$input]))
		return $dflt;
	return App::getRequest()->post[$input];
}

function berror($errors,$name) {
	if(!isset($errors[$name]))
		return '';
	return '<div class="error">'.$errors[$name].'</div>';
}