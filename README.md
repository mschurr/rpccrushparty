rpccrushparty
=============

A simple survey and match making system for Rice Program Council's annual Crush Party event.

To use this,
* Clone the repository
* Run `composer install`
* Set up a MySQL database and execute `structure.sql` and `vendor/mschurr/framework/src/schema.sql`
* Place the configuration information in `config-template.php` and rename the file to `config.php`
* Run the development server for testing `php -S localhost:80 server.php`
* Deploy using HipHop `./hhvm.sh` or any PHP-enabled web server

To add additional genders, modify `SurveyConstants`.

To add additional questions, or modify existing questions, modify `SurveyConstants` and `MatchScoreSystem`.

Requirements:
* Composer (getcomposer.org)
* MySQL > 5.0
* PHP >= 5.5
