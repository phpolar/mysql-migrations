<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

// @codeCoverageIgnoreStart

const NO_MIGRATION_NAME_ERROR = <<<'ERROR'
MigrationError: The name of the migration must be specified.
Example: mysqlmi --name CreateProductTable [command] [directory]

ERROR;
const MULTIPLE_MIGRATION_NAME_ERROR = <<<'ERROR'
MigrationError: Only one migration name must be specified.
Example: mysqlmi --name CreateProductTable [command] [directory]

ERROR;
const NO_MIGRATIONS_WARNING = <<<'WARNING'
MigrationWarning: There were either no migration files in %s
or none of the migrations implemented %s
WARNING;

// @codeCoverageIgnoreEnd
