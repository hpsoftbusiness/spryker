<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Communication;

use Pyz\Zed\Adyen\AdyenDependencyProvider;
use Spryker\Zed\Refund\Business\RefundFacadeInterface;
use SprykerEco\Zed\Adyen\Communication\AdyenCommunicationFactory as SprykerEcoAdyenCommunicationFactory;
use SprykerEco\Zed\Adyen\Dependency\Facade\AdyenToCalculationFacadeInterface;

/**
 * @method \SprykerEco\Zed\Adyen\AdyenConfig getConfig()
 * @method \Pyz\Zed\Adyen\Business\AdyenFacadeInterface getFacade()
 * @method \SprykerEco\Zed\Adyen\Persistence\AdyenEntityManagerInterface getEntityManager()
 * @method \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface getRepository()
 */
class AdyenCommunicationFactory extends SprykerEcoAdyenCommunicationFactory
{
    /**
     * @return \Spryker\Zed\Refund\Business\RefundFacadeInterface
     */
    public function getRefundFacade(): RefundFacadeInterface
    {
        return $this->getProvidedDependency(AdyenDependencyProvider::FACADE_REFUND);
    }
}
