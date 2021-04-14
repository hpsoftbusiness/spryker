<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Currency;

use Pyz\Client\Locale\LocaleClientInterface;
use Spryker\Yves\Currency\CurrencyFactory as SprykerCurrencyFactory;

class CurrencyFactory extends SprykerCurrencyFactory
{
    /**
     * @return \Pyz\Client\Locale\LocaleClientInterface
     */
    public function getLocaleClient(): LocaleClientInterface
    {
        return $this->getProvidedDependency(CurrencyDependencyProvider::CLIENT_LOCALE);
    }
}
