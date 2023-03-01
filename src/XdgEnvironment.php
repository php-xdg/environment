<?php declare(strict_types=1);

namespace Xdg\Environment;

use Xdg\Environment\Provider\ChainProvider;
use Xdg\Environment\Provider\EnvSuperGlobalProvider;
use Xdg\Environment\Provider\GetenvProvider;
use Xdg\Environment\Provider\ServerSuperGlobalProvider;

final class XdgEnvironment
{
    public static function default(): EnvironmentProviderInterface
    {
        return new ChainProvider(
            new EnvSuperGlobalProvider(),
            new ServerSuperGlobalProvider(),
            new GetenvProvider(),
        );
    }
}
