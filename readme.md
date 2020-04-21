# BQAnalytic

[![Latest Version on Packagist][ico-version]][link-packagist]
<!-- [![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci] -->

This is where your description should go. Take a look at [contributing.md](contributing.md) to see a to do list.

## Installation

Via Composer

``` bash
$ composer require rezuankassim/bqanalytic
```
Optionally you can publish the configuration file

``` bash
$ php artisan vendor:publish RezuanKassim/BQAnalytic/BQAnalyticServiceProvider
```

## Important

In your .env file, make sure you have these value setup

```
GOOGLE_CLOUD_APPLICATION_CREDENTIALS=path_to_your_credentials_file
GOOGLE_CLOUD_PROJECT_ID=your_google_cloud_project_id
BQANALYTIC_BQ_TABLE_NAME=your_bigquery_datasets_name
GOOGLE_CLOUD_APPLICATION_NAME=your_google_cloud_application_name
```

Then 

``` bash
$ php artisan migrate
```


After that

``` bash
$ php artisan db:seed --class=AnalyticSeeder
```

If you an error popup when running the command above, you need to publish the vendor file.

## Usage

Include this code into your user entity

``` php
use RezuanKassim\BQAnalytic\Traits\hasAnalyticPreferences;

....

class User extends Authenticatable
{
    use Notifiable, hasAnalyticPreferences;
```

Then run ```php artisan bqanalytic:export``` to export big query data into your local database

Note that:  ```php artisan bqanalytic:export``` can receive two date which is start date and end date behind it like ```php artisan bqanalytic:export 20200420 20200420```

Next, in your controller

``` php
use RezuanKassim\BQAnalytic\BQAnalytic;

...

$results = (new BQAnalytic(auth()->user(), Carbon::createFromFormat('d/m/Y', $range[0])->format('Ymd'), Carbon::createFromFormat('d/m/Y', $range[1])->format('Ymd')))->getAllAnalytics()[config('bqanalytic.google.accounts')[0]['name']];
```

Optionally you can enable multiple project by 

```
BQANALYTIC_MULTIPLE_PROJECTS=true
```

setting this variable in your env and publishing the config file

and insert the code below in ```google => [accounts => [here]] ```

``` php
    [
        'name' => 'YOUR_PROJECT_NAME',
        'credential' => "FULL_PATH_TO_YOUR_CREDENTIALS",
        'project' => 'PROJECT_ID',
        'auth_cache_store' => 'file',
        'client_options' => [
            'retries' => 3, // Default
        ],
        'dataset' => 'YOUR_DATASET_NAME'
    ]
```


## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/rezuankassim/bqanalytic.svg?style=flat-square
<!-- [ico-downloads]: https://img.shields.io/packagist/dt/rezuankassim/bqanalytic.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/rezuankassim/bqanalytic/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield -->

[link-packagist]: https://packagist.org/packages/rezuankassim/bqanalytic
[link-downloads]: https://packagist.org/packages/rezuankassim/bqanalytic
[link-travis]: https://travis-ci.org/rezuankassim/bqanalytic
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/rezuankassim
[link-contributors]: ../../contributors
