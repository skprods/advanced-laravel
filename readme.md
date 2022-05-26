## Advanced features for Laravel framework

### Installation:

```bash
composer require skprods/advanced-laravel ^1.0
```

After installation, connect the provider to your application.

#### Laravel

In `config/app.php`:

```php
'providers' => [
    ...,
    SKprods\AdvancedLaravel\Providers\HelpersServiceProvider::class,
]
```

#### Lumen

In `bootstrap/app.php`:
```php
$app->register(SKprods\AdvancedLaravel\Providers\HelpersServiceProvider::class);
```

### Console

`Console` is a facade for displaying information to the terminal. 
It can be used to display the script execution process.

For example:

```php
use SKprods\AdvancedLaravel\Facades\Console;

Console::info('Hello from terminal!');
```

The output differs in color depending on the type.

### Filesystem

Expanding interaction with the file system. You can 
use it to copy a file or directory to a new path.

```php
use SKprods\AdvancedLaravel\Filesystem;

$destinationPath = "/new/path/";

$sourceFile = "/path/to/file.jpg";
Filesystem::copyFile($sourceFile, $destinationPath);
// File will be accessible by the path /new/path/file.jpg

$sourceDir = "/path/to/dir";
Filesystem::copyDirectory($sourceDir, $destinationPath);
// All files of the original directory will be saved to
// the new directory. For example, /path/to/dir/file.jpg
// will be accessible by the path /new/path/file.jpg
```

### Path

Converter for path string. It converts the path according 
to certain rules:
- the path does not start with "/"
- the path to the directory ends with "/" 

For example:

```php
use SKprods\AdvancedLaravel\Path;

$path = "/some/directory/and/some/file.jpg";
Path::prepareFile($path); // some/directory/and/some/file.jpg

$path = "/some/directory/path";
Path::prepareDirectory($path) // some/directory/path/
```
