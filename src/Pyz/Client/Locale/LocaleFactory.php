<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Locale;

use NumberFormatter;
use Pyz\Client\Locale\Country\DecimalCountryFormatter;
use Pyz\Client\Locale\Country\DecimalCountryFormatterInterface;
use Pyz\Client\Locale\Country\LocaleCountryResolver;
use Pyz\Client\Locale\Country\LocaleCountryResolverInterface;
use Spryker\Client\Locale\LocaleFactory as SprykerLocaleFactory;
use Spryker\Shared\Kernel\Store;

/**
 * @method \Pyz\Client\Locale\LocaleConfig getConfig()
 */
class LocaleFactory extends SprykerLocaleFactory
{
    /**
     * @return \Pyz\Client\Locale\Country\LocaleCountryResolverInterface
     */
    public function createLocaleCountryResolver(): LocaleCountryResolverInterface
    {
        return new LocaleCountryResolver(
            $this->getStore(),
            $this->getConfig()->getCountryToLocaleRelations()
        );
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(LocaleDependencyProvider::STORE);
    }

    /**
     * @return \Pyz\Client\Locale\Country\DecimalCountryFormatterInterface
     */
    public function createDecimalCountryFormatter(): DecimalCountryFormatterInterface
    {
        return new DecimalCountryFormatter($this->createNumberFormatter());
    }

    /**
     * @return \NumberFormatter
     */
    protected function createNumberFormatter(): NumberFormatter
    {
        return new NumberFormatter(
            $this->createLocaleCountryResolver()->resolveCountryCodeForCurrentLocale(),
            NumberFormatter::DECIMAL
        );
    }
}
