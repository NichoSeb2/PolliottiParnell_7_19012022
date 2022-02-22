# PolliottiParnell_7_19012022

# Code quality
[![Maintainability](https://api.codeclimate.com/v1/badges/d4e1017c433ca2a15b4a/maintainability)](https://codeclimate.com/github/NichoSeb2/PolliottiParnell_7_19012022/maintainability)

# Prerequisite
* A Web Server (Apache, Nginx...)
* PHP 8.1
* Composer
* A Database engine (Mysql, PostgreSQL...)
* [OpenSSL command line tool](https://www.openssl.org/docs/man1.0.2/man1/openssl.html) or already generated SSH Keys
  * You will need to know the passphrase used to generate the key
* Symfony CLI
  * All requirement should be matched, to check them use : `symfony check:requirements`

## Site installation
* Clone or download the project
* Go to project folder in a terminal
* Type `composer install`
* JWT setup : the private and public pem file generated previously should be placed in the directory `config/jwt/`
* Copy `.env` to `.env.local` and edit sql, JWT (passphrase and token ttl) and app secret parameters
* Configure a new Virtual host in your web server configuration with `public/` folder as DocumentRoot

## Database setup
Type the following to setup the database :
 * `php bin/console doctrine:database:create`
 * `php bin/console doctrine:migrations:migrate`

**Important :** To be able to load the provided data, you need to set the **APP_ENV** to **dev** in your .env.local file.

To load provided data : `php bin/console doctrine:fixtures:load`

## Samples data
The example society credentials are `Demo Society` as username and `Password` as password

# Documentation link
The doc is hosted by the api itself and can be found at `/api/docs`
