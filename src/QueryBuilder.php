<?php

namespace Yasupada\YasuORM;

class QueryBuilder {
    private $table;
    private $selectColumns = '*';
    private $joins = [];
    private $whereConditions = [];
    private $groupBy = '';
    private $havingConditions = [];
    private $orderBy = '';
    private $limit = '';

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function select($columns = '*') {
        $this->selectColumns = $columns;
        return $this;
    }

    public function join($table, $condition, $type = 'INNER') {
        $this->joins[] = "$type JOIN $table ON $condition";
        return $this;
    }

    public function where($column, $operator, $value) {
        $this->whereConditions[] = "$column $operator :$column";
        return $this;
    }

    public function groupBy($columns) {
        $this->groupBy = "GROUP BY $columns";
        return $this;
    }

    public function having($column, $operator, $value) {
        $this->havingConditions[] = "$column $operator :$column";
        return $this;
    }

    public function orderBy($column, $direction = 'ASC') {
        $this->orderBy = "ORDER BY $column $direction";
        return $this;
    }

    public function limit($limit) {
        $this->limit = "LIMIT $limit";
        return $this;
    }

    public function getQuery() {
        $query = "SELECT $this->selectColumns FROM $this->table";

        if (!empty($this->joins)) {
            $query .= " " . implode(' ', $this->joins);
        }

        if (!empty($this->whereConditions)) {
            $query .= " WHERE " . implode(' AND ', $this->whereConditions);
        }

        if ($this->groupBy) {
            $query .= " $this->groupBy";
        }

        if (!empty($this->havingConditions)) {
            $query .= " HAVING " . implode(' AND ', $this->havingConditions);
        }

        if ($this->orderBy) {
            $query .= " $this->orderBy";
        }

        if ($this->limit) {
            $query .= " $this->limit";
        }

        return $query;
    }
}