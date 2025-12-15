<?php

declare(strict_types=1);

namespace Phpolar\MysqlMigrations;

// @codeCoverageIgnoreStart

const DELETE_ENTRY_STMT = <<<SQL
DELETE FROM `migration`
ORDER BY `version` DESC
LIMIT 1;
SQL;

const LAST_MIGRATION_QUERY = <<<SQL
SELECT `name`, `version`
FROM `migration`
ORDER BY `version` DESC
LIMIT 1;
SQL;

const INSERT_ENTRY_STMT = <<<SQL
INSERT INTO `migration` (`name`, `status`, `version`, `duration_ms`)
VALUES (:name, :status, :version, :duration_ms);
SQL;

const INSERT_ENTRY_WITH_ERROR_STMT = <<<SQL
INSERT INTO `migration` (`name`, `status`, `version`, `duration_ms`, `error_text`)
VALUES (:name, :status, :version, :duration_ms, :error_text);
SQL;

const COMPLETED_MIGRATIONS_QUERY = <<<SQL
SELECT CONCAT('Migration', `version`, `name`)
FROM `migration`
WHERE `status`=:status;
SQL;

// @codeCoverageIgnoreEnd
