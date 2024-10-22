<?php

declare(strict_types=1);

namespace Netglue\PsrContainer\Messenger;

class Module
{
    /**
     * @return array<string, array>
     */
    public function getConfig(): array
    {
        $provider = new ConfigProvider();

        return [
            'service_manager' => $provider->dependencies(),
            'symfony'         => [
                'messenger' => $provider->messengerConfig(),
            ],
            'laminas-cli'     => $provider->consoleConfig(),
        ];
    }
}
