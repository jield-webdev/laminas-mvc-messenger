<?php

declare(strict_types=1);

namespace Netglue\PsrContainer\Messenger\Container;

use Psr\Container\ContainerInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessageBusStaticFactory
{
    use MessageBusOptionsRetrievalBehaviour;
    use StaticFactoryContainerAssertion;

    public function __construct(private string $id)
    {
    }

    public function __invoke(ContainerInterface $container): MessageBusInterface
    {
        $options = $this->options($container, $this->id);
        $middlewareNames = $options->middleware();
        $middleware = [];
        foreach ($middlewareNames as $name) {
            $middleware[] = $container->get($name);
        }

        return new MessageBus($middleware);
    }

    /** @param mixed[] $arguments */
    public static function __callStatic(string $name, array $arguments): MessageBusInterface
    {
        $container = self::assertContainer($name, $arguments);

        return (new static($name))($container);
    }
}
