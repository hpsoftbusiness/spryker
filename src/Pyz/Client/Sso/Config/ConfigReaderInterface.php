<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso\Config;

interface ConfigReaderInterface
{
    /**
     * @return string
     */
    public function getLoginCheckPath(): string;

    /**
     * @param string $locale
     *
     * @return string
     */
    public function getAuthorizeUrl(string $locale): string;

    /**
     * @return bool
     */
    public function isSsoLoginEnabled(): bool;
}
