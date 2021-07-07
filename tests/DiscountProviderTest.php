<?php

namespace App\Tests;

use App\Model\DiscountProvider;
use PHPUnit\Framework\TestCase;

//run tests with ./vendor/bin/phpunit tests
class DiscountProviderTest extends TestCase
{

    public function testDiscountProvider(): void
    {
        $provider = new DiscountProvider();
        self::assertTrue(true);
    }
}
