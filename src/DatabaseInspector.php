<?php

namespace Yasupada\YasuORM;

class DatabaseInspector {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

    public function inspect($table) {
        $query = "PRAGMA table_info($table)";
        $stmt = $this->connection->prepare($query);
        $stmt->execute();
        $columns = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $schema = [];
        foreach ($columns as $column) {
            $schema[$column['name']] = $column['type'];
        }

        return $schema;
    }

    public function generateModel($table, $outputDir = './models') {
        $schema = $this->inspect($table);

        $className = ucfirst($table);
        $filePath = "$outputDir/$className.php";

        $content = "<?php\n\n";
        $content .= "namespace App\\Models;\n\n";
        $content .= "use Yasupada\\YasuORM\\Model;\n\n";
        $content .= "class $className extends Model {\n";
        $content .= "    protected static \$table = '$table';\n";
        $content .= "    protected static \$schema = [\n";

        foreach ($schema as $column => $type) {
            $content .= "        '$column' => '$type',\n";
        }

        $content .= "    ];\n";
        $content .= "}\n";

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        file_put_contents($filePath, $content);
        return $filePath;
    }
}