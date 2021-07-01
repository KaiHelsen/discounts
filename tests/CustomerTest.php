<?php

namespace App\Tests;

use App\Model\Customer;
use Cassandra\Custom;
use PHPUnit\Framework\TestCase;
use DateTime;

class CustomerTest extends TestCase
{
    public function testSomething(): void
    {
        self::assertTrue(true);
    }

    /**
     * test basic getter functions.
     * @throws \Exception
     */
    public function testGetters(): void
    {
        $customer1 = new Customer(1, "foo", "2015-03-03", 1200.00);
        $customer2 = new Customer(2, "bar", "2011-12-13", 2400.00);

        self::assertEquals(1, $customer1->getId(), "expected 1");
        self::assertEquals("foo", $customer1->getName(), "expected foo");
        self::assertEquals("2015-03-03", $customer1->getSinceFormatted(), "expected 2015-03-03");
        self::assertEquals(DateTime::createFromFormat('Y/m/d', "2015/03/03"), $customer1->getSince(), "expected 2015-03-03");
        self::assertEquals(1200.00, $customer1->getRevenue(), "expected 1200.00");

        self::assertEquals(2, $customer2->getId(), "expected 1");
        self::assertEquals("bar", $customer2->getName(), "expected foo");
        self::assertEquals("2011-12-13", $customer2->getSinceFormatted(), "expected 2011-12-13");
        self::assertEquals(DateTime::createFromFormat('Y/m/d', "2015/03/03"), $customer1->getSince(), "expected 2011-12-13");
        self::assertEquals(2400, $customer2->getRevenue(), "expected 1200.00");
    }

    public function testJsonConversion(): void
    {
        $customer = Customer::fromArray([
            'id'=> "1",
            "name"=>"foo",
            "since"=> "2015-03-03",
            "revenue" => "1200.00",
            ]);

        self::assertEquals(1, $customer->getId(), "expected 1");
        self::assertEquals("foo", $customer->getName(), "expected foo");
        self::assertEquals("2015-03-03", $customer->getSinceFormatted(), "expected 2015-03-03");
        self::assertEquals(1200.00, $customer->getRevenue(), "expected 1200.00");

        $converted = $customer->jsonSerialize();

        self::assertEquals(1, $converted[Customer::ID],"expected 1");
        self::assertEquals("foo", $converted[Customer::NAME], "expected foo");
        self::assertEquals("2015-03-03", $converted[Customer::SINCE], "expected 2015-03-03");
        self::assertEquals(1200.00, $converted[Customer::REVENUE], "expected 1200.00");
    }
}
