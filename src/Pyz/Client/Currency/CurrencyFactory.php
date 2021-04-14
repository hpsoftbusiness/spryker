<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Currency;

use Pyz\Client\Currency\CurrencyChange\CurrencyUpdater;
use Spryker\Client\Currency\CurrencyChange\CurrencyUpdaterInterface;
use Spryker\Client\Currency\CurrencyFactory as SprykerCurrencyFactory;

class CurrencyFactory extends SprykerCurrencyFactory
{
    /**
     * @return \Spryker\Client\Currency\CurrencyChange\CurrencyUpdaterInterface|\Pyz\Client\Currency\CurrencyChange\CurrencyUpdaterInterface
     */
    public function createCurrencyUpdater(): CurrencyUpdaterInterface
    {
        return new CurrencyUpdater(
            $this->createCurrencyBuilder(),
            $this->createCurrencyPostChangePluginExecutor(),
            $this->createCurrencyPersistence(),
            $this->getStoreClient()
        );
    }
}
