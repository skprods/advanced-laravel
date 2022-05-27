<?php

namespace SKprods\AdvancedLaravel\Eloquent;

class MultUpdater
{
    /** Название таблицы */
    protected ?string $table;

    /**
     * @var array $data - данные таблицы
     *
     * Они хранятся в формате:
     * [ field => [ [whereColumn, whereOperator, whereValue, then], ... ], ... ]
     */
    protected array $data = [];

    public function __construct(string $table = null)
    {
        $this->table = $table;
    }

    /** Получить экземпляр класса не проставляя название таблицы */
    public static function query(): self
    {
        return new self();
    }

    /** Получить экземпляр класса с проставленным названием таблицы */
    public static function table(string $table): self
    {
        return new self($table);
    }

    /** Установить таблицу */
    public function setTable(string $table): self
    {
        $this->table = $table;

        return $this;
    }

    public function setWhere(string $fieldToUpdate, string $whereColumn, string $operator, $whereValue, $then): self
    {
        $this->data[$fieldToUpdate][] = [
            'column' => $whereColumn,
            'operator' => $operator,
            'value' => $whereValue,
            'then' => $then,
        ];

        return $this;
    }

    public function setWhereIn(string $fieldToUpdate, string $whereColumn, array $in, $then): self
    {
        $this->data[$fieldToUpdate][] = [
            'column' => $whereColumn,
            'operator' => 'in',
            'value' => $in,
            'then' => $then,
        ];

        return $this;
    }

    public function setWhereNull(string $fieldToUpdate, string $whereColumn, $then): self
    {
        $this->data[$fieldToUpdate][] = [
            'column' => $whereColumn,
            'operator' => 'is null',
            'then' => $then,
        ];

        return $this;
    }

    public function setWhereNotNull(string $fieldToUpdate, string $whereColumn, $then): self
    {
        $this->data[$fieldToUpdate][] = [
            'column' => $whereColumn,
            'operator' => 'is not null',
            'then' => $then,
        ];

        return $this;
    }

    /**
     * Получить SQL запрос для обновления данных
     *
     * Обратите внимание, что если вы не установили название
     * таблицы, вместо полноценного запроса вернётся только
     * часть с SET.
     */
    public function toSql(): string
    {
        $setters = $this->prepareDataForQuery();

        $queryBegin = $this->table ? "UPDATE {$this->table}" : "";

        return trim("$queryBegin SET " . implode(', ', $setters));
    }

    /**
     * Подготовка данных к выполнению запроса.
     *
     * @return array - массив в формате
     * [ field = CASE WHEN  ]
     */
    protected function prepareDataForQuery(): array
    {
        $preparedData = [];

        foreach ($this->data as $field => $setters) {
            $query = "$field = CASE ";

            foreach ($setters as $setter) {
                $value = $setter['value'] ?? null;

                if ($value) {
                    $value = is_string($value) ? "'$value'" : $value;
                    $value = is_array($value) ? sprintf("(%s)", implode(',', $value)) : $value;
                } else {
                    $value = '';
                }

                $then = is_string($setter['then']) ? "'{$setter['then']}'" : $setter['then'];
                $then = is_null($setter['then']) ? "NULL" : $then;

                $query .= "WHEN {$setter['column']} {$setter['operator']} $value THEN $then ";
            }

            $query .= "END";

            $preparedData[] = $query;
        }

        return $preparedData;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
