<?php

declare(strict_types=1);

namespace Phpolar\Phpolar;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

const PROJECT_SIZE_THRESHOLD = 7_999;
const SRC_GLOB = "/src/{**/*.php,*.php,bin/*}";

#[CoversNothing]
final class ProjectSizeTest extends TestCase
{
    #[Test]
    #[TestDox("Source code total size shall be below " . PROJECT_SIZE_THRESHOLD . " bytes")]
    public function shallBeBelowThreshold()
    {
        $totalSize = mb_strlen(
            implode(
                preg_replace(
                    [
                        // strip comments
                        "/\/\*\*(.*?)\//s",
                        "/^(.*?)\/\/(.*?)$/s",
                    ],
                    "",
                    array_map(
                        file_get_contents(...),
                        glob(getcwd() . SRC_GLOB, GLOB_BRACE),
                    ),
                ),
            )
        );
        $this->assertGreaterThan(0, $totalSize);
        $this->assertLessThanOrEqual(PROJECT_SIZE_THRESHOLD, $totalSize);
    }
}
