<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

use DateTimeImmutable;
use Phpolar\Migrations\CreateCommand;
use Phpolar\Migrations\CreateCommandHandler;
use Phpolar\Migrations\SimpleFileWriter;
use Phpolar\Migrations\StreamLogger;
use PHPUnit\Framework\Attributes\CoversFunction;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

#[CoversFunction("Phpolar\\MysqlMigrations\\createMigration")]
final class CreateMigrationTest extends TestCase
{
    private const MIGRATION_DIR = __DIR__ . "/../__files__";
    private const MIGRATION_NAME = "CreateSomeRandomTable";
    private DateTimeImmutable $dateTime;
    private string $migrationFilename;
    private $stream;

    protected function setUp(): void
    {
        parent::setUp();

        $this->stream = fopen("php://memory", "+w");
        $this->dateTime = new DateTimeImmutable("now");
        $this->migrationFilename =
            self::MIGRATION_DIR
            . DIRECTORY_SEPARATOR
            . "Migration"
            . $this->dateTime->format("Uv")
            . self::MIGRATION_NAME
            . ".php";
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        fclose($this->stream);
        file_exists($this->migrationFilename) && unlink($this->migrationFilename);
    }

    #[Test]
    #[TestDox("Shall execute the command handler")]
    #[TestWith(["migrationName" => "CreateSomeRandomTable"])]
    public function executes(string $migrationName)
    {
        $createCommandHandler = new CreateCommandHandler(
            createCommand: new CreateCommand(
                fileWriter: new SimpleFileWriter(),
                dateTime: $this->dateTime,
            ),
            logger: $this->createStub(LoggerInterface::class),
        );

        createMigration(
            options: ["name" => $migrationName],
            migrationsDir: self::MIGRATION_DIR,
            createCommandHandler: $createCommandHandler,
            logger: $this->createStub(LoggerInterface::class),
        );

        $this->assertFileExists($this->migrationFilename);
    }

    #[Test]
    #[TestDox("Shall execute the command handler")]
    #[TestWith([false, NO_MIGRATION_NAME_ERROR])]
    #[TestWith([[], NO_MIGRATION_NAME_ERROR])]
    #[TestWith([["name" => false], NO_MIGRATION_NAME_ERROR])]
    #[TestWith([["name" => []], MULTIPLE_MIGRATION_NAME_ERROR])]
    #[TestWith([["name" => AVAILABLE_COMMANDS[0]], NO_MIGRATION_NAME_ERROR])]
    public function displaysError(
        array|false $options,
        string $expectedErrorMessage,
    ) {
        $createCommandHandler = new CreateCommandHandler(
            createCommand: new CreateCommand(
                fileWriter: new SimpleFileWriter(),
                dateTime: $this->dateTime,
            ),
            logger: new StreamLogger($this->stream),
        );

        createMigration(
            options: $options,
            migrationsDir: self::MIGRATION_DIR,
            createCommandHandler: $createCommandHandler,
            logger: new StreamLogger($this->stream),
        );

        rewind($this->stream);

        $this->assertStringContainsString($expectedErrorMessage, stream_get_contents($this->stream));
    }
}
