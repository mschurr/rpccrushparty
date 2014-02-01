<?php

function sql_keys($array) {
	$keys = '';

	foreach($array as $field)
		$keys .= '`'.$field.'`,';

	$keys = substr($keys, 0, -1);

	return $keys;
}

function sql_values($array) {
	$keys = '';

	foreach($array as $field)
		$keys .= ':'.$field.',';

	$keys = substr($keys, 0, -1);

	return $keys;
}