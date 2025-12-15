<?php

declare(strict_types=1);

use PhpContrib\Migration\MigrationInterface;
use PhpContrib\Migration\MigrationRunStatus;

final readonly class Migration1765596308882AddColumnToRandomTable implements MigrationInterface
{
    public function up(PDO $connection): MigrationRunStatus
    {
        return $connection->exec(
            <<<SQL
            ALTER TABLE `some_random_table`
            ADD COLUMN `test1` VARCHAR(100) NOT NULL;
            SQL,
        ) !== false ? MigrationRunStatus::COMPLETED : MigrationRunStatus::FAILED;
    }

    public function down(PDO $connection): MigrationRunStatus
    {
        return $connection->exec(
            <<<SQL
            ALTER TABLE `some_random_table` DROP COLUMN `test1`;
            SQL,
        ) !== false ? MigrationRunStatus::COMPLETED : MigrationRunStatus::FAILED;
    }
}
