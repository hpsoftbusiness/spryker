<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Locale\Plugin\Locale;

use Spryker\Yves\Locale\Plugin\Locale\LocaleLocalePlugin as SprykerLocaleLocalePlugin;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Client\Locale\LocaleClientInterface getClient()
 * @method \Pyz\Yves\Locale\LocaleFactory getFactory()
 * @method \Pyz\Yves\Locale\LocaleConfig getConfig()
 */
class LocaleLocalePlugin extends SprykerLocaleLocalePlugin
{
    public const HEADER_ACCEPT_LANGUAGE = 'accept-language';

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @return string|null
     */
    protected function getRequestUri(): ?string
    {
        return $this->getRequest()->server->get(static::REQUEST_URI);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequest(): Request
    {
        if ($this->request === null) {
            $this->request = Request::createFromGlobals();
        }

        return $this->request;
    }
}
