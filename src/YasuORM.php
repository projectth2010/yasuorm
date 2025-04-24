<?php

namespace Yasupada\YasuORM;

use Yasupada\YasuORM\DatabaseConnection;
use Yasupada\YasuORM\Model;
use Yasupada\YasuORM\QueryBuilder;

class YasuORM {
    public static function connect($dsn, $username = null, $password = null) {
        return new DatabaseConnection($dsn, $username, $password);
    }
}