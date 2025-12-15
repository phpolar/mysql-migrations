<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

use PDO;
use Pdo\Mysql;
use PDOStatement;
use Phpolar\Migrations\StreamLogger;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

#[CoversFunction("Phpolar\\MysqlMigrations\\runMigrations")]
final class RunMigrationsTest extends TestCase
{
    private string $migrationsDir;
    private PDO $connection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->migrationsDir = dirname(__DIR__) . "/__migrations__";

        $this->connection = new Mysql(
            dsn: sprintf(
                "mysql:dbname=%s;host=%s;port=%s;charset=utf8mb4",
                getenv("MYSQL_DATABASE"),
                getenv("MYSQL_HOST"),
                getenv("MYSQL_PORT"),
            ),
            username: getenv("MYSQL_USER"),
            password: getenv("MYSQL_PASSWORD"),
            options: [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            ]
        );

        $this->connection->exec(
            <<<SQL
            SET SESSION sql_mode = 'STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
        SQL
        );
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->connection->exec(
            <<<SQL
            DROP TABLE IF EXISTS `some_random_table`;
            SQL,
        );
        $this->connection->exec(
            <<<SQL
            TRUNCATE `migration`;
            SQL,
        );
    }

    #[Test]
    #[TestDox("Shall run existing migrations")]
    public function runsMigrations()
    {
        runMigrations(
            migrationsDir: $this->migrationsDir,
            connection: $this->connection,
            logger: new StreamLogger(STDERR),
        );

        $this->assertSame(
            'some_random_table',
            $this->connection
                ->query("SHOW TABLES LIKE 'some_random_table'")
                ->fetchColumn(),
        );

        $stmt = $this->connection->query(
            <<<SQL
            SELECT * FROM `some_random_table`;
            SQL,
        );


        $this->assertNotFalse($stmt);

        $this->assertInstanceOf(PDOStatement::class, $stmt);

        assert($stmt instanceof PDOStatement);

        $items = $stmt->fetchAll();

        $this->assertCount(6, $items);

        $this->assertSame("name1", $items[0]->name);
        $this->assertSame("name2", $items[1]->name);
        $this->assertSame("name3", $items[2]->name);
        $this->assertSame("name4", $items[3]->name);
        $this->assertSame("name5", $items[4]->name);
        $this->assertSame("name6", $items[5]->name);
        $this->assertSame("desc1", $items[0]->description);
        $this->assertSame("desc2", $items[1]->description);
        $this->assertSame("desc3", $items[2]->description);
        $this->assertSame("desc4", $items[3]->description);
        $this->assertSame("desc5", $items[4]->description);
        $this->assertSame("desc6", $items[5]->description);
    }
}
