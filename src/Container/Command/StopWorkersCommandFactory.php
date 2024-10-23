<?php

declare(strict_types=1);

namespace Netglue\PsrContainer\Messenger\Container\Command;

use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\Command\StopWorkersCommand;

final class StopWorkersCommandFactory
{
    public function __invoke(ContainerInterface $container): StopWorkersCommand
    {
        return new StopWorkersCommand($container->get('messenger.routable_message_bus'));
    }
}
