<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Communication;

use Pyz\Service\UtilPolling\UtilPollingServiceInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Pyz\Zed\MyWorldPayment\Business\MyWorldPaymentFacadeInterface getFacade()
 * @method \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig getConfig()
 */
class MyWorldPaymentCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Pyz\Service\UtilPolling\UtilPollingServiceInterface
     */
    public function getUtilPollingService(): UtilPollingServiceInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::SERVICE_UTIL_POLLING);
    }
}
