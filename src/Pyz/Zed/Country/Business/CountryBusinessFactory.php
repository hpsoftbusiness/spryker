<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country\Business;

use Spryker\Zed\Country\Business\CountryBusinessFactory as SprykerCountryBusinessFactory;
use Spryker\Zed\Country\Business\CountryManagerInterface;

/**
 * @method \Pyz\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface getQueryContainer()
 */
class CountryBusinessFactory extends SprykerCountryBusinessFactory
{
    /**
     * @return \Spryker\Zed\Country\Business\CountryManagerInterface
     */
    public function createCountryManager(): CountryManagerInterface
    {
        return new CountryManager(
            $this->getQueryContainer(),
            $this->getConfig()
        );
    }
}
