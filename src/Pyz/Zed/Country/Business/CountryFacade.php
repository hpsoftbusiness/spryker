<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\Country\Business;

use Pyz\Zed\Sales\Dependency\Facade\SalesToCountryInterface;
use Spryker\Zed\Country\Business\CountryFacade as SrpykerCountryFacade;

/**
 * @method \Spryker\Zed\Country\Business\CountryBusinessFactory getFactory()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 */
class CountryFacade extends SrpykerCountryFacade implements SalesToCountryInterface
{
}
