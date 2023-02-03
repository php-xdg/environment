<?php declare(strict_types=1);

namespace Xdg\Environment\Tests;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Xdg\Environment\Provider\ChainProvider;
use Xdg\Environment\XdgEnvironment;

final class XdgEnvironmentTest extends TestCase
{
    public function testDefaultIsChainProvider(): void
    {
        $env = XdgEnvironment::default();
        Assert::assertInstanceOf(ChainProvider::class, $env);
    }
}
