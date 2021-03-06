<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Messenger;

use Spryker\Client\Messenger\MessengerClient as SprykerMessengerClient;
use Spryker\Shared\Messenger\MessengerConfig;

class MessengerClient extends SprykerMessengerClient implements MessengerClientInterface
{
    /**
     * @return string[]
     */
    public function getFlashErrorMessages(): array
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $flashBag */
        $flashBag = $this->getFlashBag();

        return $flashBag->get(MessengerConfig::FLASH_MESSAGES_ERROR);
    }

    /**
     * @return string[]
     */
    public function getFlashSuccessMessages(): array
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $flashBag */
        $flashBag = $this->getFlashBag();

        return $flashBag->get(MessengerConfig::FLASH_MESSAGES_SUCCESS);
    }

    /**
     * @return string[]
     */
    public function getFlashInfoMessages(): array
    {
        /** @var \Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface $flashBag */
        $flashBag = $this->getFlashBag();

        return $flashBag->get(MessengerConfig::FLASH_MESSAGES_INFO);
    }
}
