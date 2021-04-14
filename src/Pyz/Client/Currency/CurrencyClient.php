<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Currency;

use Spryker\Client\Currency\CurrencyClient as SprykerCurrencyClient;

/**
 * @method \Pyz\Client\Currency\CurrencyFactory getFactory()
 */
class CurrencyClient extends SprykerCurrencyClient implements CurrencyClientInterface
{
    /**
     * @param string $localeName
     *
     * @return void
     */
    public function setCurrentCurrencyByLocale(string $localeName): void
    {
        $this->getFactory()->createCurrencyUpdater()->setCurrentCurrencyByLocale($localeName);
    }
}
