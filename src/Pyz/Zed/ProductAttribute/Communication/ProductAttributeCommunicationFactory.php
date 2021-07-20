<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Communication;

use Pyz\Zed\ProductAttribute\ProductAttributeDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;

/**
 * @method \Pyz\Zed\ProductAttribute\Persistence\ProductAttributeQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\ProductAttribute\ProductAttributeConfig getConfig()
 * @method \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface getFacade()
 */
class ProductAttributeCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    public function getMoneyFacade(): MoneyFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributeDependencyProvider::FACADE_MONEY);
    }
}
