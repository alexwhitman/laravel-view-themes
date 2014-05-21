# Laravel View Themes

View themes is a simple package to provide themed view support to Laravel.

## Installation

Add `alexwhitman/view-themes` to the `require` section of your `composer.json` file.

`"alexwhitman/view-themes": "1.1.x"`

Run `composer update` to install the latest version.

## Setup

This package extends Laravels built in `ViewServiceProvider`, so that provider must be replaced in `app/config/app.php`.
Replace the instance of `'Illuminate\View\ViewServiceProvider',` with `'AlexWhitman\ViewThemes\ViewThemesServiceProvider',`.

## Configuration

The default settings are for the themes to be in a `themes` directory in `app/` with the default theme called `default`.

```
app/
    themes/
        default/
            views/
```

To change these defaults the package config will need to be published with `artisan config:publish alexwhitman/view-themes`.
The new config file, `app/config/packages/alexwhitman/view-themes/config.php`, can then be customised as required.

## Usage

A standard call to `View::make('index')` will look for an index view in `app/themes/default/views/`. However, if a theme is specified with
`$app['view.finder']->setCurrentTheme('my-theme');` prior to calling `View::make()` then the view will first be looked for in `app/themes/my-theme/views`.
If the view is not found for the current theme the default theme will then be searched.

### Facade

The `ViewTheme` facade can also be used if preferred `ViewTheme::setCurrentTheme('my-theme')` by adding an entry for `AlexWhitman\ViewThemes\ThemeFacade` to `app/config/app.php`.

## Example

Given a directory structure of
```
app/
    themes/
        default/
            views/
                layout.blade.php
                admin.blade.php
        my-theme/
            views/
                layout.blade.php
```

```
View::make('layout'); // Loads app/themes/default/views/layout.blade.php

$app['view.finder']->setCurrentTheme('my-theme');

View::make('layout'); // Loads app/themes/my-theme/views/layout.blade.php
View::make('admin'); // Loads app/themes/default/views/layout.blade.php
```

## Changelog

### 1.1.2

- Add function to get current theme path

### 1.1.1

- Clear previous paths on initialise

### 1.1.0

- Update for Laravel 4.1

### 1.0.0

- Initial release
