# YasuORM

A lightweight and flexible ORM for PHP developed by Yasupada Group.  
Supports SQLite, MySQL, PostgreSQL, and more.

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