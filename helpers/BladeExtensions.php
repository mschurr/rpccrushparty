<?php


function berror(&$errors,$name) {
	if(!isset($errors[$name]))
		return '';
	return '<div class="error">'.$errors[$name].'</div>';
}
