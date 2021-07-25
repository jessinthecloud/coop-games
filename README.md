Rewrite of [Build a Video Game Aggregator](https://laracasts.com/series/build-a-video-game-aggregator)

## Running in Docker

- Boot Docker and start a container
  - make sure docker is using WSL and the correct Linux distro:
    - Docker -> settings -> resources -> WSL integration -> Enable integration with additional distros
- Open windows terminal with a Linux tab
  - To open a new tab using Linux, use the caret symbol next to the `+`
- `cd` to the working directory you want
  - for example from `/mnt/c/Users/Jess/Documents/` to `/mnt/g/Jess/Code`
  - Access the current folder via Windows with `explorer.exe .`
- run the [Laravel install command](https://laravel.com/docs/8.x/installation#getting-started-on-windows)
- run the command given to start Laravel Sail
  - For instance, `cd coop-games && ./vendor/bin/sail up`
  - > The first time you run the Sail up command, Sail's application containers will be built on your machine. 
    > This could take several minutes. Don't worry, subsequent attempts to start Sail will be much faster.
  - Once the application's Docker containers have been started, you can access the application in your web browser at: http://localhost

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.
