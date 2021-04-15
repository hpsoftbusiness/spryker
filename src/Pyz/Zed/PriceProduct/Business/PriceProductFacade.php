<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProduct\Business;

use Spryker\Zed\PriceCartConnector\Dependency\Facade\PriceCartToPriceProductInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductFacade as SprykerPriceProductFacade;

/**
 * @method \Pyz\Zed\PriceProduct\Business\PriceProductBusinessFactory getFactory()
 */
class PriceProductFacade extends SprykerPriceProductFacade implements
    PriceProductFacadeInterface,
    PriceCartToPriceProductInterface
{
    /**
     * @return string
     */
    public function getSPBenefitPriceTypeName(): string
    {
        return $this->getFactory()->getConfig()->getPriceTypeSPBenefitName();
    }
}
