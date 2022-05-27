`Model` - расширение базового класса `Illuminate\Database\Eloquent\Model`
для ваших Eloquent-моделей.

Класс основан на подходе к проектированию, при котором весь функционал
наполнения модели и её сохранения должен находиться в самом классе этой
модели. Для реализации такого подхода класс содержит несколько статических
методов.

#### make()

Метод `make()` служит для наполнения модели данными, не сохраняя их
в базу данных. В стандартной версии этого метода класс наполняется 
с помощью `fill()`.

app/Models/User.php:

```php
use SKprods\AdvancedLaravel\Eloquent\Model;

class User extends Model
{
     protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'password',
    ];
}
```

Наполнение модели будет выглядеть так:

```php
$data = [
    'first_name' => 'Pavel',
    'last_name' => 'Abramov',
    'username' => 'abramovp',
    'email' => 'abramovp@test.ru',
    'password' => '12345',
];

$user = User::make($data);
```

В случае, если модель связана с другими, можно дополнить этот метод
собственным функционалом. Например:

```php
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use SKprods\AdvancedLaravel\Eloquent\Model;

class Article extends Model
{
     protected $fillable = [
        'title',
        'text',
    ];
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public static function make(array $params): Model
    {
        $model = parent::make($params);
        
        $user = $params['user'];
        $model->user()->associate($user);
        
        return $model;
    }
}
```

#### create()

Метод `create()` делает то же самое, что и `make()` за исключением
того, что после создание экземпляра модели она сохраняется в БД

```php
$data = [
    'first_name' => 'Pavel',
    'last_name' => 'Abramov',
    'username' => 'abramovp',
    'email' => 'abramovp@test.ru',
    'password' => '12345',
];

$user = User::make($data);   // не сохранит в БД
$user = User::create($data); // сохранит в БД
```

#### createMany()

Метод `createMany()` позволяет создать сразу несколько записей в БД.
Он принимает на вход массив массивов с данными для модели. В нашем
примере с `User` выше это будет выглядеть так:

```php
$data = [
    [
        'first_name' => 'Pavel',
        'last_name' => 'Abramov',
        'username' => 'abramovp',
        'email' => 'abramovp@test.ru',
        'password' => '12345',
    ],
    [
        'first_name' => 'Oleg',
        'last_name' => 'Telegov',
        'username' => 'telegov',
        'email' => 'telegov@test.ru',
        'password' => '12345',
    ],
];

User::createMany($data);  // создаст 2 записи в БД и вернёт true
```

#### updateMany()

Метод `updateMany()` позволяет обновить несколько записей одним
запросом. Этот метод использует конструкцию 
`update table set = case ... end` для обновления данных.

Аргументом ожидается наполненный экземпляр `MultUpdater`. Вернёт
метод число затронутых строк.

Пример использования:

```php
use SKprods\AdvancedLaravel\Eloquent\MultUpdater;

$data = [
    ['id' => 1, 'field' => 'fieldValue'],
    ['id' => 2, 'field' => 'fieldValue'],
    ['id' => 3, 'field' => 'fieldValue'],
    ['id' => 4, 'field' => 'fieldValue2'],
    ['id' => 5, 'field' => 'fieldValue2'],
    ['id' => 6, 'field' => 'fieldValue3'],
];

$updater = MultUpdater::table('tableName');

foreach ($data as $item) {
    $updater->setWhere('field', 'id', '=', $item['id'], $item['field']);
}

Model::updateMany($updater);
```

При вызове метода сформируется код:

```sql
UPDATE tableName 
SET field = CASE
    WHEN id = 1 THEN 'fieldValue'
    WHEN id = 2 THEN 'fieldValue'
    WHEN id = 3 THEN 'fieldValue'
    WHEN id = 4 THEN 'fieldValue2'
    WHEN id = 5 THEN 'fieldValue2'
    WHEN id = 6 THEN 'fieldValue3'
END
```

Также есть методы для добавления проверок на IN и NULL:

```php
use SKprods\AdvancedLaravel\Eloquent\MultUpdater;

MultUpdater::table('tableName')
    ->setWhere('field1', 'id', '=', 1, 'field1Value')
    ->setWhereIn('field2', 'id', [2,3,4,5], 'field2Value')
    ->setWhereNull('field3', 'status', 'field3Value')
    ->setWhereNotNull('field4', 'status', 'field4Value');
```

Такая структура сформирует следующий SQL запрос:

```sql
UPDATE tableName 
SET 
field1 = CASE 
    WHEN id = 1 THEN 'field1Value' 
END, 
field2 = CASE 
    WHEN id in (2,3,4,5) THEN 'field2Value' 
END, 
field3 = CASE
    WHEN status is null THEN 'field3Value' 
END, 
field4 = CASE 
    WHEN status is not null THEN 'field4Value' 
END
```

#### Особенность MultUpdater

Обратите внимание, что создать экземпляр `MultUpdater` можно
двумя способами:

```php
use SKprods\AdvancedLaravel\Eloquent\MultUpdater;

MultUpdater::table('tableName');
MultUpdater::query();
```

В первом случае в конструктор запроса будет сразу проставлено
название таблицы и итоговый запрос в `->toSql()` будет содержать
полноценный update-запрос.

Во втором случае таблица не будет проставлена и итоговый запрос в
`->toSql()` вернёт только структуру `SET ... END`, без начала с
`UPDATE tableName`. 

Это может быть полезно в случаях, когда вы хотите добавить к запросу
дополнительные условия, например, ограничивая затрагиваемые записи
конструкцией `WHERE id IN (1,2,3,4,5)`.
