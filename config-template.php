<?php

Config::set(array(
	'database.driver'   => 'mysql',
	'database.host'     => 'localhost',
	'database.port'     => '3306',
	'database.user'     => 'httpd',
	'database.pass'     => 'httpd',
	'database.name'     => 'crushparty',
	'crush.expired'     => false,
	'cookies.secretKey' => 'somethingrandom',
	'mailer.name'       => 'Crush Party Results',
	'mailer.email'      => 'donotreply@riceapps.org',
	'mailer.host'       => 'mail.riceapps.org',
	'mailer.port'       => '465',
	'mailer.user'       => 'donotreply@riceapps.org',
	'mailer.pass'       => '',
	'mailer.crypt'      => 'ssl'
));

if(php_sapi_name() == 'cli-server') {
	Config::set(array(
		'database.host'     => 'localhost',
		'database.port'     => '3306',
		'database.user'     => 'httpd',
		'database.pass'     => 'httpd',
		'database.name'     => 'crushparty'
	));
}