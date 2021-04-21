<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Messenger;

use Spryker\Client\Messenger\MessengerClientInterface as SprykerMessengerClientInterface;

interface MessengerClientInterface extends SprykerMessengerClientInterface
{
    /**
     * Specification:
     * - Returns array of errors saved in session for flash messages.
     * - Unsets flash error messages from session.
     *
     * @return string[]
     */
    public function getFlashErrorMessages(): array;

    /**
     * Specification:
     * - Returns array of success messaged saved in session for flash.
     * - Unsets flash success messages from session.
     *
     * @return string[]
     */
    public function getFlashSuccessMessages(): array;

    /**
     * Specification:
     * - Returns array of info messaged saved in session for flash.
     * - Unsets flash info messages from session.
     *
     * @return string[]
     */
    public function getFlashInfoMessages(): array;
}
