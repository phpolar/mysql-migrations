<?php

declare(strict_types=1);

use PhpContrib\Migration\MigrationInterface;
use PhpContrib\Migration\MigrationRunStatus;

final readonly class Migration1765596282708CreateSomeRandomTable implements MigrationInterface
{
    public function up(PDO $connection): MigrationRunStatus
    {
        return $connection->exec(
            <<<SQL
            CREATE TABLE IF NOT EXISTS `some_random_table` (
                `id` INT NOT NULL AUTO_INCREMENT,
                `name` VARCHAR(100) NOT NULL,
                `description` VARCHAR(100) NOT NULL,
                PRIMARY KEY(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
            SQL,
        ) !== false
            ? MigrationRunStatus::COMPLETED : MigrationRunStatus::FAILED;
    }

    public function down(PDO $connection): MigrationRunStatus
    {
        return $connection->exec(
            <<<SQL
            DROP TABLE IF EXISTS `some-random-table`;
            SQL,
        ) !== false
            ? MigrationRunStatus::COMPLETED : MigrationRunStatus::FAILED;
    }
}
