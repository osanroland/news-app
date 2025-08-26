<?php

require_once '/var/www/vendor/autoload.php';

use App\Config\Database;

echo "Setting up News Application Database (DEBUG MODE)...\n\n";

try {
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    echo "Creating database schema...\n";
    $schema = file_get_contents('/var/www/database/schema.sql');
    $pdo->exec($schema);
    echo "Schema created successfully!\n\n";

    echo "Seeding database with sample data...\n";
    $seedFile = '/var/www/database/seed_data.sql';

    if (!file_exists($seedFile)) {
        echo "Seed file not found!\n";
        exit(1);
    }

    $seedData = file_get_contents($seedFile);

    $statements = array_filter(array_map('trim', explode(';', $seedData)));

    foreach ($statements as $index => $statement) {
        $trimmed = trim($statement);
        if (!empty($trimmed)) {
            echo "Statement " . ($index + 1) . ": " . substr($trimmed, 0, 60) . "...\n";

            if (str_starts_with($trimmed, '--')) {
                continue;
            }

            try {
                $result = $pdo->exec($statement);
                echo "   Success - Affected rows: " . $result . "\n";
            } catch (Exception $e) {
                echo "   Error: " . $e->getMessage() . "\n";
                echo "   Full statement: $statement\n";
            }
        }
    }

    echo "\n Seeding completed!\n\n";
    echo "\n Setup completed!\n";

} catch (Exception $e) {
    echo "Setup failed: " . $e->getMessage() . "\n";
    exit(1);
}
?>
