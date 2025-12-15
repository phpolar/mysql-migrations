<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

use Phpolar\Migrations\CreateCommandHandler;
use Psr\Log\LoggerInterface;

/**
 * Create a migration
 *
 * @param array<string,false>|array<string,list<mixed>>|array<string,string> $options
 */
function createMigration(
    array|false $options,
    string $migrationsDir,
    CreateCommandHandler $createCommandHandler,
    LoggerInterface $logger,
): void {
    if (
        is_array($options) === false
        || \array_key_exists("name", $options) === false
    ) {
        $logger->error(NO_MIGRATION_NAME_ERROR);
        return;
    }

    ["name" => $migrationName] = $options;

    if ($migrationName === false) {
        $logger->error(NO_MIGRATION_NAME_ERROR);
        return;
    }

    if (is_array($migrationName) === true) {
        $logger->error(MULTIPLE_MIGRATION_NAME_ERROR);
        return;
    }

    if (in_array($migrationName, AVAILABLE_COMMANDS) === true) {
        $logger->error(NO_MIGRATION_NAME_ERROR);
        return;
    }

    $createCommandHandler->create(path: $migrationsDir, name: $migrationName);
}
