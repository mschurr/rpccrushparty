<?php
Config::set(array(
	'database.driver'   => 'mysql',
	'database.host'     => 'localhost',
	'database.port'     => '3306',
	'database.user'     => '',
	'database.pass'     => '',
	'database.name'     => '',
	'crush.expired'     => (time() > 999999999999999 ? true : false),
	'crush.publish'     => (time() > 999999999999999 ? true : false),
	'cookies.secretKey' => '',
	'mailer.name'       => '@riceapps.org',
	'mailer.email'      => '@riceapps.org',
	'mailer.host'       => '',
	'mailer.port'       => '465',
	'mailer.user'       => '@riceapps.org',
	'mailer.pass'       => '',
	'mailer.crypt'      => 'ssl',
	'recaptcha.publicKey' => '',
	'recaptcha.privateKey' => '',

  'app.development' => true,

  'auth.driver' => 'cas',
  'users.driver' => 'cas',
  'groups.driver' => 'db',
  'auth.driver' => 'cas',

  'auth.cas.host' => 'netid.rice.edu',
  'auth.cas.port' => 443,
  'auth.cas.path' => '/cas',
  'auth.cas.cert' => FILE_ROOT.'/rice-cas.pem',
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
