<p align="center">
    <a href="https://lumen.laravel.com/" target="_blank">
        <img src="https://raw.githubusercontent.com/adiyansahcode/adiyansahcode/main/assets/lumen-icon.svg" height="100">
    </a>
</p>

# Lumen API AUTH
Rest API project use [Lumen](https://lumen.laravel.com/) with format [jsonapi](https://jsonapi.org/)

## Installation
* Clone this project
* Create .env file `cp .env.example .env`
* Run composer `composer install`
* Generate App key `php artisan key:generate`
* Generate Jwt key `php artisan jwt:secret`
* Run migration and seeder `php artisan migrate:fresh --seed`
* Run server `php -S localhost:8014 -t public`
* Import postman json to your postman `Lumen API Juke.postman_collection`
* done, just try run your project in postman

## Included Packages

- [Dingo API](https://github.com/dingo/api)
- [Fractal](https://github.com/thephpleague/fractal)
- [Guzzle Http](https://github.com/guzzle/guzzle)
- [Lumen Generator](https://github.com/flipboxstudio/lumen-generator)
- [Tymon jwt-auth](https://github.com/tymondesigns/jwt-auth)
