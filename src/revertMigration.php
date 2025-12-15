<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

use PDO;
use Phpolar\Migrations\GetLastMigrationQuery;
use Phpolar\Migrations\RevertCommand;
use Phpolar\Migrations\RevertCommandHandler;
use Psr\Log\LoggerInterface;

/**
 * Reverts the last migration.
 */
function revertMigration(
    string $migrationsDir,
    PDO $connection,
    LoggerInterface $logger,
): void {

    foreach (glob($migrationsDir . DIRECTORY_SEPARATOR . "*.php") as $migration) {
        require_once $migration;
    }

    $lastMigrationQuery = new GetLastMigrationQuery(
        connection: $connection,
        lastMigrationQuery: LAST_MIGRATION_QUERY,
    );

    $lastMigration = $lastMigrationQuery->query();

    if ($lastMigration === false) {
        $logger->notice(
            "MigrationNotice: The last migration was not returned."
        );
        return;
    }


    new RevertCommandHandler(
        revertCommand: new RevertCommand(
            migration: $lastMigration,
            connection: $connection,
            migrationRecordDeleteStatement: DELETE_ENTRY_STMT,
        ),
        logger: $logger,
    )->revert();
}
