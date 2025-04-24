<?php

namespace Yasupada\YasuORM;

abstract class Model {
    protected static $table;
    protected static $connection;

    public static function setConnection($connection) {
        self::$connection = $connection;
    }

    public static function queryBuilder() {
        return new QueryBuilder();
    }

    public static function all() {
        $queryBuilder = self::queryBuilder();
        $query = $queryBuilder->table(static::$table)->getQuery();

        $stmt = self::$connection->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function find($id) {
        $queryBuilder = self::queryBuilder();
        $query = $queryBuilder->table(static::$table)
                              ->where('id', '=', $id)
                              ->getQuery();

        $stmt = self::$connection->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public static function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $query = "INSERT INTO " . static::$table . " ($columns) VALUES ($placeholders)";
        $stmt = self::$connection->prepare($query);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        return $stmt->execute();
    }
}