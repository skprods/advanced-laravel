### Filesystem

С помощью класса `Filesystem` вы можете взаимодействовать с файловой
системой. Например, скопировать файл или каталог по новому пути:

```php
use SKprods\AdvancedLaravel\Filesystem;

$destinationPath = "/new/path/";

$sourceFile = "/path/to/file.jpg";
Filesystem::copyFile($sourceFile, $destinationPath);
// Файл будет доступен по новому пути /new/path/file.jpg

$sourceDir = "/path/to/dir";
Filesystem::copyDirectory($sourceDir, $destinationPath);
// Все файлы в исходной директории будут скопированы
// в новую. Например, /path/to/dir/file.jpg
// будет доступен по пути /new/path/file.jpg
```

### Path

Конвертер для пути. Он преобразует путь в соответствии со
следующими правилами:

- путь не должен начинаться на "/";
- путь к директории всегда оканчивается на "/";
- несколько / подряд меняются на один.

Например:

```php
use SKprods\AdvancedLaravel\Path;

$path = "/some/directory///and/some/file.jpg";
Path::prepareFile($path); // some/directory/and/some/file.jpg

$path = "/some/directory/path";
Path::prepareDirectory($path) // some/directory/path/
```