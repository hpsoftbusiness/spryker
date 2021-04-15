<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProduct\Business;

use Pyz\Zed\PriceProduct\Business\Internal\Install;
use Spryker\Zed\PriceProduct\Business\Internal\InstallInterface;
use Spryker\Zed\PriceProduct\Business\PriceProductBusinessFactory as SprykerPriceProductBusinessFactory;

/**
 * @method \Pyz\Zed\PriceProduct\PriceProductConfig getConfig()
 */
class PriceProductBusinessFactory extends SprykerPriceProductBusinessFactory
{
    /**
     * @return \Spryker\Zed\PriceProduct\Business\Internal\InstallInterface
     */
    public function createInstaller(): InstallInterface
    {
        return new Install(
            $this->createPriceTypeWriter(),
            $this->getConfig()
        );
    }
}
