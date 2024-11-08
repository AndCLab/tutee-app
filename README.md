<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

-   [Simple, fast routing engine](https://laravel.com/docs/routing).
-   [Powerful dependency injection container](https://laravel.com/docs/container).
-   Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
-   Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
-   Database agnostic [schema migrations](https://laravel.com/docs/migrations).
-   [Robust background job processing](https://laravel.com/docs/queues).
-   [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

-   **[Vehikl](https://vehikl.com/)**
-   **[Tighten Co.](https://tighten.co)**
-   **[WebReinvent](https://webreinvent.com/)**
-   **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
-   **[64 Robots](https://64robots.com)**
-   **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
-   **[Cyber-Duck](https://cyber-duck.co.uk)**
-   **[DevSquad](https://devsquad.com/hire-laravel-developers)**
-   **[Jump24](https://jump24.co.uk)**
-   **[Redberry](https://redberry.international/laravel/)**
-   **[Active Logic](https://activelogic.com)**
-   **[byte5](https://byte5.de)**
-   **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Initial Pull:

1. `git init`
2. `git remote add origin https://github.com/Mark-Ameka/tutee-app.git`
3. `git branch -M main`
4. `git pull origin main`
5. `git fetch`

## Pull:

1. `git pull origin main`

## Push:

1. `git add .`
2. `git commit -m 'your-message'`
3. `git push -u origin your-branch`

## Merge:

###### Make sure your changes are already committed

> If not, do commit before merging

1. `git add .`
2. `git commit`

> If branch is already up to date, make sure you pull before merging

1. `git pull origin branch`

> After, just run this command

1. `git merge branch-you-wanna-merge`

## Switch Branch:

> `mark` is a branch name

1. `git checkout mark`

## Things you need

1. composer
2. xampp
3. ide

## Install

1. `composer install`
2. `npm install`
3. `composer require laravel/socialite`
4. Copy `.env.example` and rename it to `.env`
5. `php artisan key:generate`
6. `php artisan migrate`
7. `npm run build`

## Run the project: separate terminal

-   `php artisan serve`
-   `npm run dev`

## For Reverb:

-   `php artisan reverb:start`

## To generate a `Volt` Component

> `counter` is your volt component name

```php
php artisan make:volt counter --class
```

## Volt Component

> You must include the following in your class component

-   `#[Layout('layouts.app')]`
-   `use Livewire\Attributes\Layout;`

##### Example

```php
<?php

use Livewire\Volt\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.app')] class extends Component {

}; ?>

<div>
    //
</div>
```

> ##### Read more about [Livewire and Volt](https://livewire.laravel.com/docs/quickstart)

## WireUI Component

> Add `wui` in every wireui component

-   `x-wui-alert`

##### Example

```php
<x-wui-alert title="Success Message!" positive flat />
```

> ##### Read more about [Wuire UI](https://wireui.dev/components/alert)

#### Choose your modules [here :)](https://docs.google.com/spreadsheets/d/1TNNyQHMk4bLhPNshhT1BhvTgkBRifpaCwuu311HIenY/edit#gid=0)

| Tools                                                        | User Interface                                        |
| ------------------------------------------------------------ | :---------------------------------------------------- |
| [Laravel Livewire]()                                         | [Tailwind](https://tailwindcss.com/docs/installation) |
| [Laravel Reverb](https://reverb.laravel.com/)                | [WireUI](https://wireui.dev/components/alert)         |
| [Laravel Socialite](https://laravel.com/docs/11.x/socialite) | [Php Flasher](https://php-flasher.io/livewire/)       |
| [Laravel Evoyer](https://envoyer.io/)                        | [DaisyUI](https://daisyui.com/)                       |
| [Laravel Breeze](https://laravel.com/docs/11.x/starter-kits) |

[Back to top](#top)
