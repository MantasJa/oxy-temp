<?php

namespace Unit\Util;

use App\Util\CacheTags;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Util\CacheTags
 */
class CacheTagsTest extends TestCase
{
    public function testRawValue(): void
    {
        $this->assertSame('user_', CacheTags::USER->value);
    }

    public function testCorrectTag(): void
    {
        $userId = 123;
        $actualTag = CacheTags::USER->withId($userId);

        $this->assertIsString($actualTag);
        $this->assertSame('user_123', $actualTag);
    }
}
