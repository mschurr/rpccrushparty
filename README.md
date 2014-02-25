rpccrushparty
=============

A simple match survey and match making system for Rice Program Council's annual Crush Party event.

To use this,
* Clone the repository
* Run `composer install`
* Set up a MySQL database and execute `structure.sql`
* Place the configuration information in `config-template.php` and rename the file to `config.php`
* Run the development server for testing `php -S localhost:80 server.php`
* Deploy using HipHop `./hhvm.sh` or any PHP-enabled web server

Requirements:
* Composer
* MySQL
* PHP >= 5.3 (Recommended 5.5)
