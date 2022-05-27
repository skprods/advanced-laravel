<?php

namespace SKprods\AdvancedLaravel\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Facades\DB;

abstract class Model extends BaseModel
{
    /**
     * Создание экземпляра модели без сохранения в БД
     *
     * @return static
     */
    public static function make(array $params): Model
    {
        $model = new static();
        $model->fill($params);

        return $model;
    }

    /**
     * Создание экземпляра модели с сохранением в БД
     *
     * @return static
     */
    public static function create(array $params): Model
    {
        $model = static::make($params);
        $model->save();

        return $model;
    }

    /** Создание нескольких моделей с сохранением в БД */
    public static function createMany(array $params): bool
    {
        $models = new Collection();

        foreach ($params as $modelParam) {
            $models->push(static::make($modelParam));
        }

        return static::query()->insert($models->toArray());
    }

    /**
     * Обновление нескольких записей
     *
     * @param MultUpdater $updater
     * @return int - число обновлённых записей
     */
    public static function updateMany(MultUpdater $updater): int
    {
        $table = (new static())->getTable();

        return DB::update($updater->setTable($table)->toSql());
    }
}
