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

``` bash
GOOGLE_CLOUD_APPLICATION_CREDENTIALS=path_to_your_credentials_file
GOOGLE_CLOUD_PROJECT_ID=your_google_cloud_project_id
BQANALYTIC_BQ_TABLE_NAME=your_bigquery_datasets_name
```

After that

``` bash
$ php artisan db:seed --class=AnalyticSeeder
```

If you an error popup when running the command above, you need to publish the vendor file.

## Usage

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
