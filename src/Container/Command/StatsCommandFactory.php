<?php

declare(strict_types=1);

namespace Netglue\PsrContainer\Messenger\Container\Command;

use Netglue\PsrContainer\Messenger\Container\Util;
use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Command\StatsCommand;

final class StatsCommandFactory
{
    public function __invoke(ContainerInterface $container): StatsCommand
    {
        $transports = Util::transportConfiguration($container);

        return new StatsCommand($container, $transports);
    }
}
