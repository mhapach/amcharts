# amcharts

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Build Status][ico-travis]][link-travis]
[![StyleCI][ico-styleci]][link-styleci]

This package is a Laravel (7.0+) wrapper which helps you create AmCharts charts

## Installation

Add AmCharts lib to your project via Composer

``` bash
$ composer require mhapach/amcharts
```
## Configuration

Copy views to your project:
``` bash
php artisan vendor:publish --provider="mhapach\AmCharts\Providers\AmChartsProvider"
```

## Usage
In your controller create instance of AmChart with name of chart that corresponds to directory structure in your resource/views/vendor/amcharts 

        $chart = new AmChart('xy.column_stacked');
        $chart->setLibraries([
            'https://cdn.amcharts.com/lib/5/index.js',
            "https://cdn.amcharts.com/lib/5/xy.js",
            'https://cdn.amcharts.com/lib/5/themes/Animated.js',
            'https://cdn.amcharts.com/lib/5/themes/Responsive.js',
            'https://cdn.amcharts.com/lib/5/locales/ru_RU.js',
        ]);

        $chart->setData(
            [[
                "year"=> "2021",
                "europe"=> 2.5,
                "namerica"=> 2.5,
                "asia"=> 2.1,
                "lamerica"=> 1,
                "meast"=> 0.8,
                "africa"=> 0.4
            ], [
                "year"=> "2022",
                "europe"=> 2.6,
                "namerica"=> 2.7,
                "asia"=> 2.2,
                "lamerica"=> 0.5,
                "meast"=> 0.4,
                "africa"=> 0.3
            ], [
                "year"=> "2023",
                "europe"=> 2.8,
                "namerica"=> 2.9,
                "asia"=> 2.4,
                "lamerica"=> 0.3,
                "meast"=> 0.9,
                "africa"=> 0.5
            ]]

        );

        print $chart->render();
     
   
## Change log

Please see the [changelog](changelog.md) for more information on what has changed recently.

## Contributing

Please see [contributing.md](contributing.md) for details and a todolist.

## Security

If you discover any security related issues, please email author email instead of using the issue tracker.

## Credits

- [author name][link-author]
- [All Contributors][link-contributors]

## License

license. Please see the [license file](license.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/mhapach/projectversions.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/mhapach/projectversions.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/mhapach/projectversions/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/12345678/shield

[link-packagist]: https://packagist.org/packages/mhapach/projectversions
[link-downloads]: https://packagist.org/packages/mhapach/projectversions
[link-travis]: https://travis-ci.org/mhapach/projectversions
[link-styleci]: https://styleci.io/repos/12345678
[link-author]: https://github.com/mhapach
[link-contributors]: ../../contributors
