<?php

declare(strict_types=1);

use PhpContrib\Migration\MigrationInterface;
use PhpContrib\Migration\MigrationRunStatus;

final readonly class Migration1765596307085AddDataToRandomTable implements MigrationInterface
{
    public function up(PDO $connection): MigrationRunStatus
    {
        return $connection->exec(
            <<<SQL
            INSERT INTO `some_random_table` (`name`, `description`)
            VALUES ('name1', 'desc1'),
                ('name2', 'desc2'),
                ('name3', 'desc3'),
                ('name4', 'desc4'),
                ('name5', 'desc5'),
                ('name6', 'desc6');
            SQL
        ) !== false ? MigrationRunStatus::COMPLETED : MigrationRunStatus::FAILED;
    }

    public function down(PDO $connection): MigrationRunStatus
    {
        return $connection->exec(
            <<<SQL
            DELETE FROM `some_random_table`
            WHERE `name` IN (
                'name1',
                'name2',
                'name3',
                'name4',
                'name5',
                'name6'
            );
            SQL,
        ) !== false ? MigrationRunStatus::COMPLETED : MigrationRunStatus::FAILED;
    }
}
