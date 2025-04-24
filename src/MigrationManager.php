<?php

namespace Yasupada\YasuORM;

class MigrationManager {
    private $connection;
    private $migrationTable = 'migrations';

    public function __construct($connection) {
        $this->connection = $connection;
        $this->ensureMigrationTable();
    }

    private function ensureMigrationTable() {
        $query = "CREATE TABLE IF NOT EXISTS {$this->migrationTable} (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            version TEXT NOT NULL,
            applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
    }

    public function getAppliedVersions() {
        $query = "SELECT version FROM {$this->migrationTable} ORDER BY applied_at ASC";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        return array_column($stmt->fetchAll(\PDO::FETCH_ASSOC), 'version');
    }

    public function applyMigration($version, $upQuery) {
        $stmt = $this->connection->prepare($upQuery);
        if ($stmt->execute()) {
            $query = "INSERT INTO {$this->migrationTable} (version) VALUES (:version)";
            $stmt = $this->connection->prepare($query);
            $stmt->execute(['version' => $version]);
        }
    }

    public function rollbackMigration($version, $downQuery) {
        $stmt = $this->connection->prepare($downQuery);
        if ($stmt->execute()) {
            $query = "DELETE FROM {$this->migrationTable} WHERE version = :version";
            $stmt = $this->connection->prepare($query);
            $stmt->execute(['version' => $version]);
        }
    }
}