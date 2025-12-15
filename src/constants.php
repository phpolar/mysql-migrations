<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

// @codeCoverageIgnoreStart

const AVAILABLE_COMMANDS = ["run", "revert", "create"];
const DEFAULT_MIGRATIONS_FOLDER = "migrations";
const DB_CONNECTION_FILE = "./connection.php";
const MIGRATION_GLOB = "Migration[1-9][1-9][1-9][1-9][1-9][1-9]*.php";

// @codeCoverageIgnoreEnd
