<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Country\Business;

use Spryker\Zed\Country\Business\CountryFacade as SprykerCountryFacade;

/**
 * @method \Spryker\Zed\Country\Business\CountryBusinessFactory getFactory()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 */
class CountryFacade extends SprykerCountryFacade implements CountryFacadeInterface
{
}
