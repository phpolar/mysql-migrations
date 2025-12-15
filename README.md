
<!-- markdownlint-disable MD033-->
<!-- markdownlint-disable MD041-->
<h1 align="center">
    <img src="./phpolar.svg" alt="Application Logo" width="120px" height="120px" /><br>
    PHPolar MySql Migrations
</h1>
<p align=center>
Adds support for running migrations against a MySql database.
</p>

# PHPolar Migration Runner (for MySqL)

## DESCRIPTION

    A command line tool that supports database migration management

## USAGE

    mysqlmi [options] command

## AVAILABLE COMMANDS

    create -  Generates a stub migration in the specified directory
    run    -  Executes all pending database migrations located in the specified directory
    revert -  Reverts the last successfully run migration

## ARGUMENTS

    directory -  The directory that contains the migrations

## OPTIONS

    -h, --help Display this help message
    -n, --name The name of the migration to create

## SETUP

    Create a connection.php file in your working directory that returns an instance of PDO.
    Specify the directory containing the migrations when running the run -  or create command.
    If a directory is not supplied, the script will use a migrations folder in the working directory.

## CREATING A MIGRATION

    "Migration" and a 13 digit timestamp will be prepended to the filename.
    For example, mysqlmi --name CreateProductTable create ./migrations
    will generate a file named Migration1764993752674CreateProductTable.php in the [36mmigrations folder.

## EXAMPLES

    mysqlmi --name CreateProductTable create ./migrations
    mysqlmi run ./migrations
    mysqlmi revert
