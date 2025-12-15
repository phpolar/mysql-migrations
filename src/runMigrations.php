<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

use PDO;
use PhpContrib\Migration\MigrationInterface;
use Phpolar\Migrations\GetPendingMigrationsQuery;
use Phpolar\Migrations\RunCommand;
use Phpolar\Migrations\RunCommandHandler;
use Psr\Log\LoggerInterface;

/**
 * Runs all pending migrations.
 */
function runMigrations(
    string $migrationsDir,
    PDO $connection,
    LoggerInterface $logger,
): void {

    $filesInMigrationDir = glob(
        join(
            DIRECTORY_SEPARATOR,
            [
                rtrim($migrationsDir, DIRECTORY_SEPARATOR),
                MIGRATION_GLOB,
            ]
        )
    );

    if ($filesInMigrationDir === false) {
        $logger->error("An error occurred retrieving files in " . $migrationsDir);
        return;
    }

    foreach ($filesInMigrationDir as $fileInMigrationDir) {
        require_once $fileInMigrationDir;
    }

    $migrationCandidates = array_filter(
        array_map(
            static fn(string $path) => basename($path, ".php"),
            $filesInMigrationDir,
        ),
        static fn(string $filename) => is_subclass_of($filename, MigrationInterface::class)
    );

    if (count($migrationCandidates) === 0) {
        $logger->notice(
            sprintf(
                NO_MIGRATIONS_WARNING,
                $migrationsDir,
                MigrationInterface::class,
            )
        );
        return;
    }

    new RunCommandHandler(
        runCommand: new RunCommand(
            connection: $connection,
            insertMigrationResultStmt: INSERT_ENTRY_STMT,
            insertMigrationResultWithErrorStmt: INSERT_ENTRY_WITH_ERROR_STMT,
        ),
        pendingMigrationQuery: new GetPendingMigrationsQuery(
            connection: $connection,
            candidates: $migrationCandidates,
            completedMigrationsQuery: COMPLETED_MIGRATIONS_QUERY,
        ),
        logger: $logger
    )->run();
}
