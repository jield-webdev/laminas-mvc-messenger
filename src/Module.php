<?php

declare(strict_types=1);

namespace Netglue\PsrContainer\Messenger;

class Module
{
    public function getConfig(): array
    {
        $provider = new ConfigProvider();
        return [
            'service_manager' => $provider->dependencies(),
            'symfony' => [
                'messenger' => $provider->messengerConfig(),
            ],
            'laminas-cli' => $provider->consoleConfig(),
        ];
    }
}
