<?php

namespace Src\DB\Elements;

use DebugTools\Debug;
use Exception;
use Src\DB\Operators;
use Src\DB\QueryBuilder;

class Model
{
    protected static string $table = '';

    private array $cache = []; // место хранения информации о модели, по типу id, name и прочее (для обращения как с объектом, это в __get)
    // protected static string $idField = 'id';
    protected static array $fields = [];

    public function __construct(array $data = [])
    {
        // контроль заполнения
        foreach (static::$fields as $field) {
            if (array_key_exists($field, $data)) {
                $this->cache[$field] = $data[$field];
            }
        }
    }

    public function __get(string $field): mixed
    {
        return $this->cache[$field] ?? null;
    }

    public function __set(string $field, string|int|float|null $value): void
    {
        if (in_array($field, static::$fields)) {
            $this->cache[$field] = $value;
        }
    }

    public function __isset(string $field): bool
    {
        return isset($this->cache[$field]); // для магии
    }

    public static function query(): QueryBuilder
    {
        return new QueryBuilder(static::$table, static::class);
    } 

    public static function where(string $field, string|int|float|null $value = null, Operators $operator = Operators::EQUAL): QueryBuilder
    {
        return static::query()->where($field, $value, $operator);
    }

    public static function find(int $id): ?static
    {
        return static::query()->where('id', $id)->first();
    }

    public static function first(): ?static
    {
        return static::query()->first();
    }

    public static function all(): array
    {
        return static::query()->get();
    }

    public function save(): void
    {
        if ($this->id) {
            // $this->update($this->cache);
            static::query()->where('id', $this->id)->update($this->cache);
        } else {
            // $this->insert($this->cache);
            $this->cache['id'] = static::query()->insert($this->cache);
        }
    }

    public function delete(): void
    {
        if (!$this->id) {
            throw new Exception('Cant delete without ID!');
        }

        static::query()->where('id', $this->id)->delete();
    }
}