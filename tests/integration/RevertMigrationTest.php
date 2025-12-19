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

#[CoversFunction("Phpolar\\MysqlMigrations\\revertMigration")]
final class RevertMigrationTest extends TestCase
{
    private PDO $connection;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (glob(dirname(__DIR__) . "/__migrations__/*.php") as $migration) {
            require_once $migration;
        }

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

        $this->connection->exec(
            <<<SQL
            CREATE TABLE IF NOT EXISTS `some_random_table` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(100) NOT NULL,
                `description` VARCHAR(100) NOT NULL,
                PRIMARY KEY(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            SQL,
        );

        $stmt = $this->connection->prepare(INSERT_ENTRY_STMT);
        $stmt->execute([
            "name" => "CreateSomeRandomTable",
            "version" => "1765596282708",
            "status" => "COMPLETED",
            "duration_ms" => "0",
        ]);

        $stmt = $this->connection->exec(
            <<<SQL
            INSERT INTO `some_random_table` (`name`, `description`)
            VALUES ('name1', 'desc1'),
                ('name2', 'desc2'),
                ('name3', 'desc3'),
                ('name4', 'desc4'),
                ('name5', 'desc5'),
                ('name6', 'desc6');
            SQL
        );

        $stmt = $this->connection->prepare(INSERT_ENTRY_STMT);
        $stmt->execute([
            "name" => "AddDataToRandomTable",
            "version" => "1765596307085",
            "status" => "COMPLETED",
            "duration_ms" => "0",
        ]);
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
    #[TestDox("Shall revert the last migration")]
    public function revertsLastMigration()
    {
        revertMigration(
            migrationsDir: dirname(__DIR__) . "/__migrations__/",
            connection: $this->connection,
            logger: new StreamLogger(STDERR),
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

        $this->assertCount(0, $items);
    }
}
