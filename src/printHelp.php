<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

/**
 * Display help message to the console.
 *
 * @codeCoverageIgnore
 */
function printHelp(): void
{
    fwrite(
        STDOUT,
        <<<HELP
\e[34m==========================================
|| PHPolar Migration Runner (for MySqL) ||
========================================== \e[0m

\e[33mDESCRIPTION:\e[0m
    A command line tool that supports database migration management

\e[33mUSAGE:\e[0m
    mysqlmi [options] command

\e[33mAVAILABLE COMMANDS:\e[0m
    \e[32mcreate\e[0m Generates a stub migration in the specified directory
    \e[32mrun\e[0m Executes all pending database migrations located in the specified directory
    \e[32mrevert\e[0m Reverts the last successfully run migration

\e[33mARGUMENTS:\e[0m
    \e[32mdirectory\e[0m The directory that contains the migrations

\e[33mOPTIONS:\e[0m
    \e[32m-h, --help\e[0m Display this help message
    \e[32m-n, --name\e[0m The name of the migration to create

\e[33mSETUP:\e[0m
    Create a connection.php file in your working directory that returns an instance of PDO.
    Specify the directory containing the migrations when running the \e[32mrun\e[0m or create command.
    If a directory is not supplied, the script will use a migrations folder in the working directory.

\e[33mCREATING A MIGRATION:\e[0m
    "Migration" and a 13 digit timestamp will be prepended to the filename.
    For example, \e[37mmysqlmi --name CreateProductTable create ./migrations\e[0m
    will generate a file named \e[36mMigration1764993752674CreateProductTable.php\e[0m in the \e[36mmigrations\e[0m folder.

\e[33mEXAMPLES:\e[0m
    mysqlmi --name CreateProductTable create ./migrations
    mysqlmi run ./migrations
    mysqlmi revert

HELP,
    );
}
