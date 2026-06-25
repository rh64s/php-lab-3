<?php

namespace Src\DB;

use Exception;
use mysqli_result;
use mysqli;
use mysqli_sql_exception;
use Src\DB\Operators;

// queries chain-like
class QueryBuilder
{
    private ?mysqli $connection;
    private string $table;
    private string $modelClass;
    private array $wheres = [];
    private array $joins = [];
    private ?int $limit = null;
    private array $selected = ['*'];

    public function __construct(string $table, string $modelClass = '')
    {
        $this->connection = Manager::single()->getConnection();
        $this->table = $table;
         $this->modelClass = $modelClass;
    }

    public function select(?array $columns = null)
    {
        if (!is_null($columns)){
            if (!in_array('id', $columns)) {
                $columns = array_merge($columns, ['id']);
            }
            $this->selected = $columns;
        }
        return $this;
    }


    public function where(string $field, int|float|string|null $value = null, Operators $operator = Operators::EQUAL)
    {
        $this->wheres[] = [
            'field' => $field,
            'operator' => $operator->get(),
            'value' => $value
        ];
        return $this;
    }

    public function join(Joins $joins, string $table, string $whereFirst, string $whereSecond, Operators $operator = Operators::EQUAL)
    {
        $this->joins[] = $joins->get() . " JOIN " . $this->connection->real_escape_string($table) . " ON " . $this->connection->real_escape_string($whereFirst) . " " . $operator->get() . " " . $this->connection->real_escape_string($whereSecond);
    }

    public function get(): array
    {
        $result = $this->connection->query($this->buildQuery());

        if ($result instanceof mysqli_result) {
            $rows = [];
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $result->free();

             if ($this->modelClass && empty($this->joins)) {
                 return array_map(
                     fn($row) => new $this->modelClass($row),
                     $rows
                 );
             }
            return $rows;
        }
        return [];
    }

    public function exists(): bool
    {
        return !empty($this->get());
    }

    public function first(): array|object|null
    {
        $this->limit = 1;
        $result = $this->get();
        return $result[0] ?? null;
    }

    public function insert(array $data): int
    {
        $fields = implode(', ', array_keys($data));
        $values = implode(', ', array_map(
            fn($v) => "'" . $this->connection->real_escape_string($v) . "'",
            array_values($data)
        ));

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($values)";

        try {
            $this->connection->query($sql);
        } catch (mysqli_sql_exception $e) {
            throw new Exception('Insert failed: ' . $this->connection->error);
        }

        return (int) $this->connection->insert_id;
    }

    public function delete(): int
    {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . $this->buildWhereConditions();
        }
        try {
            $this->connection->query($sql);
        } catch (mysqli_sql_exception $e) {
            throw new Exception('Delete failed: ' . $this->connection->error);
        }
        return $this->connection->affected_rows;
    }

    public function update(array $data): int
    {
        $sets = [];
        foreach($data as $field => $value) {
            $sets[] = $field . " = '" . $this->connection->real_escape_string($value) . "'";
        }
        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets);

        if (!empty($this->wheres)) {
            $sql .= " WHERE " . $this->buildWhereConditions();
        }

        try {
            $this->connection->query($sql);
        } catch (mysqli_sql_exception $e) {
            throw new Exception('Update failed: ' . $this->connection->error);
        }

        return $this->connection->affected_rows;
    }

    private function buildQuery(): string
    {
        $query = "SELECT " . implode(', ', $this->selected) . " FROM " . $this->table . " ";
        if (!empty($this->joins)) {
            foreach ($this->joins as $join) {
                $query .= " " . $join . " ";
            }
        }
        if (!empty($this->wheres)) {
            $query .= "WHERE " . $this->buildWhereConditions();
        }

        if (!is_null($this->limit)) {
            $query .= " LIMIT {$this->limit}";
        }
        return $query;
    }

    private function buildWhereConditions(): string
    {
        $conditions = [];
        foreach ($this->wheres as $where) {
//            if (is_null($where['value'])) {
//                $conditions[] = $where['field'] . " " . $where['operator']; // это под null
//            }
            if (in_array($where['operator'], ['IN', 'NOT IN'])) {
                $conditions[] = $where['field'] . " " . $where['operator'] . "(" .
                    implode(', ', array_map(
                        fn($value) => "'" . $this->connection->real_escape_string($value) . "'",
                         $where['value']))
                    . ")" ;
            } else {
                $conditions[] = $where['field'] . " " . $where['operator'] . " '" . $this->connection->real_escape_string($where['value']) . "'";
            }
        }
        return implode(' AND ', $conditions);
    }
}