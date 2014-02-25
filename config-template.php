<?php
Config::set(array(
	'database.driver'   => 'mysql',
	'database.host'     => 'localhost',
	'database.port'     => '3306',
	'database.user'     => '',
	'database.pass'     => '',
	'database.name'     => '',
	'crush.expired'     => false,
	'cookies.secretKey' => '',
	'mailer.name'       => '',
	'mailer.email'      => '',
	'mailer.host'       => '',
	'mailer.port'       => '465',
	'mailer.user'       => '',
	'mailer.pass'       => '',
	'mailer.crypt'      => 'ssl',
	'recaptcha.publicKey' => '',
	'recaptcha.privateKey' => ''
));

/**
 * Set separate configuration values for local development.
 */
if(php_sapi_name() == 'cli-server') {
	Config::set(array(
		'database.host'     => 'localhost',
		'database.port'     => '3306',
		'database.user'     => 'httpd',
		'database.pass'     => 'httpd',
		'database.name'     => 'crushparty'
	));
}