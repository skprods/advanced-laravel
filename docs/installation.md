Скачайте исходных код с помощью команды:

```bash
composer require skprods/advanced-laravel ^1.0
```

После установки подключите провайдер к вашему приложению.on.

#### Laravel

В файле `config/app.php`:

```php
'providers' => [
    ...,
    SKprods\AdvancedLaravel\Providers\HelpersServiceProvider::class,
]
```

#### Lumen

В файле `bootstrap/app.php`:
```php
$app->register(SKprods\AdvancedLaravel\Providers\HelpersServiceProvider::class);
```