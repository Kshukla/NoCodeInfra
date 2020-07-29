# NoCodeInfra

## Introduction
* This is a NoCodeInfra for Terraform with enhancemenets and many modules pre-made, just for you.
* The project is taken to Laravel 5.6 so we can develop from the latest Laravel.

## Features
This code contains features of generate dynamic terraform code by UI. User can create multiple AWS workbooks and manage it. 

## Additional Features
* Pages Module
* Blog Module
* FAQ Module

Give your project a Head Start by using [NoCodeInfra](https://github.com/Kshukla/NoCodeInfra).

## Installation

Please check the official laravel installation guide for server requirements before you start. [Official Documentation](https://laravel.com/docs/5.6/installation#installation)


Clone the repository

    git clone https://github.com/Kshukla/NoCodeInfra.git

Switch to the repo folder

    cd NoCodeInfra

If you have linux system, you can execute the command below only in your project root

    1) sudo chmod -R 777 install.sh
    2) ./install.sh

Create environment file from example file, And make database connection and configuration.

    cp .env.example .env
	
Install composer
		
	composer install

Install the javascript dependencies using npm

    npm install

Compile the dependencies

    npm run development

For linking storage folder in public

    php artisan storage:link
	
Generate a new application key

    php artisan key:generate

Run the database migrations (**Set the database connection in .env before migrating**)

    php artisan migrate

Run the database seeders

    php artisan db:seed
	
Install Passport

    php artisan passport:install

For generating the files of unisharp file manager

    php artisan vendor:publish --tag=lfm_public

Start the local development server

    php artisan serve


You can now access the server at http://localhost:8000

**Command list**

	git clone https://github.com/Kshukla/NoCodeInfra.git
	cd NoCodeInfra
	cp .env.example .env
	composer install
	npm install
	npm run development
	php artisan storage:link
	php artisan key:generate
	php artisan migrate
	php artisan db:seed
	php artisan passport:install
	php artisan vendor:publish --tag=lfm_public


## Please note

- To run test cases, add SQLite support to your php

## Other Important Commands
- To fix php coding standard issues run - composer format
- To perform various self diagnosis tests on your Laravel application. run - php artisan self-diagnosis
- To clear all cache run - composer clear-all
- To built Cache run - composer cache-all
- To clear and built cache run - composer cc

## Logging In

`php artisan db:seed` adds three users with respective roles. The credentials are as follows:

* Administrator: `admin@admin.com`
* Backend User: `executive@executive.com`
* Default User: `user@user.com`

Password: `1234`

## Issues

If you come across any issues please report them [here](https://github.com/Kshukla/NoCodeInfra/issues).

## Contributing
Feel free to create any pull requests for the project. For proposing any new changes or features you want to add to the project, you can send us an email at following addresses.

    (1) Kapil Shukla - kshukla@gmail.com
    (2) Paras Patoliya - paraspatel2020@gmail.com

## License

[MIT LICENSE](https://github.com/Kshukla/NoCodeInfra/blob/master/LICENSE.txt)
