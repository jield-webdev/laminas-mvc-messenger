<?php

declare(strict_types=1);

namespace Netglue\PsrContainer\Messenger;

use Laminas\ServiceManager\Factory\InvokableFactory;
use Laminas\ServiceManager\ServiceManager;
use Symfony\Component\Messenger as SymfonyMessenger;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;

/**
 * @psalm-import-type ServiceManagerConfiguration from ServiceManager
 * @psalm-type RetryStrategyConfig = array{
 *     service?: string|null,
 *     max_retries?: numeric|null,
 *     delay?: numeric|null,
 *     multiplier?: numeric|null,
 *     max_delay?: numeric|null,
 * }
 * @psalm-type TransportSetup = array{
 *     dsn: non-empty-string,
 *     retry_strategy?: RetryStrategyConfig,
 *     failure_transport?: non-empty-string|null,
 * }
 * @psalm-type BusConfig = array{
 *     allows_zero_handlers: bool,
 *     middleware: list<string>,
 *     handler_locator: class-string<HandlersLocatorInterface>,
 *     handlers: array<string, string|list<string>>,
 *     routes: array<string, list<string>>,
 *     logger?: string|null,
 * }
 * @psalm-type MessengerConfig = array{
 *     logger?: string|null,
 *     failure_transport?: string|null,
 *     buses: array<string, BusConfig>,
 *     transports: array<string, TransportSetup>,
 * }
 */
final class ConfigProvider
{
    /** @return array<string, mixed> */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->dependencies(),
            'symfony'      => [
                'messenger' => $this->messengerConfig(),
            ],
            'laminas-cli'  => $this->consoleConfig(),
        ];
    }

    /** @return ServiceManagerConfiguration */
    public function dependencies(): array
    {
        return [
            'factories' => [
                Container\FailureReceiversProvider::class                  => Container\FailureReceiversProviderFactory::class,
                Container\FailureSendersProvider::class                    => Container\FailureSendersProviderFactory::class,
                SymfonyMessenger\Command\ConsumeMessagesCommand::class     => Container\Command\ConsumeCommandFactory::class,
                SymfonyMessenger\Command\DebugCommand::class               => Container\Command\DebugCommandFactory::class,
                SymfonyMessenger\Command\FailedMessagesRetryCommand::class => Container\Command\FailedMessagesRetryCommandFactory::class,
                SymfonyMessenger\Command\StatsCommand::class               => Container\Command\StatsCommandFactory::class,
                SymfonyMessenger\Command\StopWorkersCommand::class         => Container\Command\StopWorkersCommandFactory::class,
                RetryStrategyContainer::class                              => Container\RetryStrategyContainerFactory::class,
                TransportFactoryFactory::class                             => InvokableFactory::class,
            ],
        ];
    }

    /** @return MessengerConfig */
    public function messengerConfig(): array
    {
        return [
            // This logger is used by the console commands:
            'logger'            => null,
            // The name of the failure transport should be retrievable by name from the container:
            'failure_transport' => null, //'failed',
            'buses'             => [],
            'transports'        => [],
        ];
    }

    /** @return array<string, array<string, class-string>> */
    public function consoleConfig(): array
    {
        return [
            'commands' => [
                'messenger:consume'               => SymfonyMessenger\Command\ConsumeMessagesCommand::class,
                'messenger:debug'                 => SymfonyMessenger\Command\DebugCommand::class,
                //                'messenger:failed-messages-remove' => SymfonyMessenger\Command\FailedMessagesRemoveCommand::class,
                'messenger:failed-messages-retry' => SymfonyMessenger\Command\FailedMessagesRetryCommand::class,
                //                'messenger:failed-messages-show'   => SymfonyMessenger\Command\FailedMessagesShowCommand::class,
                'messenger:stats'                 => SymfonyMessenger\Command\StatsCommand::class,
                'messenger:stop-workers'          => SymfonyMessenger\Command\StopWorkersCommand::class,
            ],
        ];
    }
}
