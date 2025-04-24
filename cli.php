<?php

require_once 'vendor/autoload.php';

use Yasupada\YasuORM\DatabaseConnection;
use Yasupada\YasuORM\MigrationManager;

if ($argc < 2) {
    echo "Usage: php cli.php [command] [options]\n";
    echo "Commands:\n";
    echo "  migrate       - Apply all pending migrations\n";
    echo "  upgrade       - Apply the next migration\n";
    echo "  downgrade     - Rollback the last migration\n";
    exit(1);
}

$dsn = 'sqlite:/path/to/database.sqlite';
$connection = (new DatabaseConnection($dsn))->getConnection();
$migrationManager = new MigrationManager($connection);

$command = $argv[1];

switch ($command) {
    case 'migrate':
        $this->applyAllMigrations($migrationManager);
        break;
    case 'upgrade':
        $this->applyNextMigration($migrationManager);
        break;
    case 'downgrade':
        $this->rollbackLastMigration($migrationManager);
        break;
    default:
        echo "Invalid command.\n";
}

function applyAllMigrations($migrationManager) {
    $migrationsDir = __DIR__ . '/migrations/';
    $appliedVersions = $migrationManager->getAppliedVersions();

    foreach (glob($migrationsDir . '*.php') as $file) {
        $version = basename($file, '.php');
        if (!in_array($version, $appliedVersions)) {
            require $file;
            echo "Applying migration: $version\n";
            $migrationManager->applyMigration($version, $upQuery);
        }
    }
}

function applyNextMigration($migrationManager) {
    $migrationsDir = __DIR__ . '/migrations/';
    $appliedVersions = $migrationManager->getAppliedVersions();
    $pendingMigrations = array_diff(
        array_map('basename', glob($migrationsDir . '*.php'), ['.php']),
        $appliedVersions
    );

    if (empty($pendingMigrations)) {
        echo "No pending migrations.\n";
        return;
    }

    $nextVersion = min($pendingMigrations);
    $file = $migrationsDir . "$nextVersion.php";
    require $file;
    echo "Applying migration: $nextVersion\n";
    $migrationManager->applyMigration($nextVersion, $upQuery);
}

function rollbackLastMigration($migrationManager) {
    $appliedVersions = $migrationManager->getAppliedVersions();
    if (empty($appliedVersions)) {
        echo "No migrations to rollback.\n";
        return;
    }

    $lastVersion = end($appliedVersions);
    $file = __DIR__ . "/migrations/$lastVersion.php";
    require $file;
    echo "Rolling back migration: $lastVersion\n";
    $migrationManager->rollbackMigration($lastVersion, $downQuery);
}