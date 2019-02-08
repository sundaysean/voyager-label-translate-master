# Voyager Label Translate Master

Create Label Translate For Multi Languages (Voyager)

## Installation

Install using composer:

```bash
composer require kravanh/voyager-label-translate
```

Add LabelTranslateServiceProvider to the providers array of your Laravel v5.4 application's config/app.php

```php
'providers' => [
    KRAVANH\LabelTranslateServiceProvider::class,
],
```

## Usage

Create Key In Backend Then Go to View Call:

```blade
@lang('your_key') or __('your_key')
