# Voyager Label Translate Master

Create Label Translate For Multi Languages (Voyager)

## Installation

Install using composer:

```bash
composer require kravanh/voyager-label-translate
```

Then add the service provider to the configuration:

```php
'providers' => [
    KRAVANH\LabelTranslateServiceProvider::class,
],
```

## Usage

Create Key In Backend Then Go to View Call:

```blade
@lang('your_key') or __('your_key')
