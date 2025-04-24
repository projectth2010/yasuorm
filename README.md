# YasuORM

A lightweight and flexible ORM for PHP developed by Yasupada Group.  
Supports SQLite, MySQL, PostgreSQL, and more.

## Features
- **Model First**: Create database tables from models.
- **Database First**: Generate models from existing database tables.
- **Migrations**: Manage database schema changes with `migrate`, `upgrade`, and `downgrade`.
- **CRUD Operations**: Easily perform Create, Read, Update, and Delete operations.
- **Query Builder**: Build dynamic SQL queries with a fluent interface.

## Installation

Install via Composer:

```bash
composer require yasupada/yasuorm
```

## Usage

```php
use Yasupada\YasuORM\YasuORM;
use Yasupada\YasuORM\Model;

// Connect to database
$dsn = 'sqlite:/path/to/database.sqlite';
$connection = YasuORM::connect($dsn);

// Set connection for the model
Model::setConnection($connection->getConnection());

// Fetch all users
$users = User::all();
print_r($users);
```

## Models

```php
use Yasupada\YasuORM\Model;

class User extends Model {
    protected static $table = 'users';
    protected static $schema = [
        'id' => 'INTEGER PRIMARY KEY AUTOINCREMENT',
        'name' => 'TEXT NOT NULL',
        'email' => 'TEXT NOT NULL UNIQUE'
    ];
}
```

## Perform CRUD Operation

```php
// Set connection for the model
User::setConnection($connection);

// Create a new user
User::create(['name' => 'John Doe', 'email' => 'john@example.com']);

// Fetch all users
$users = User::all();
print_r($users);

// Find a user by ID
$user = User::find(1);
print_r($user);
```

## Migrations

Use the CLI tool to manage database schema changes.
Apply All Pending Migrations

```bash
php cli.php migrate
php cli.php upgrade
php cli.php downgrade

```

## Database First

Generate Models from Existing Tables (Database First)

```bash
php cli.php inspect users
```


## Contributing

Contributions are welcome!
