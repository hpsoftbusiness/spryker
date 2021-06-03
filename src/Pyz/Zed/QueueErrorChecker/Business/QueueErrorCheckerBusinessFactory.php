<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\QueueErrorChecker\Business;

use Pyz\Zed\Queue\Business\QueueFacadeInterface;
use Pyz\Zed\QueueErrorChecker\Business\Model\QueueErrorMessenger;
use Pyz\Zed\QueueErrorChecker\Business\Model\QueueErrorMessengerInterface;
use Pyz\Zed\QueueErrorChecker\QueueErrorCheckerDependencyProvider;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\QueueErrorChecker\QueueErrorCheckerConfig getConfig()
 */
class QueueErrorCheckerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\QueueErrorChecker\Business\Model\QueueErrorMessengerInterface
     */
    public function createQueueErrorMessage(): QueueErrorMessengerInterface
    {
        return new QueueErrorMessenger($this->getQueueFacade());
    }

    /**
     * @return \Pyz\Zed\Queue\Business\QueueFacadeInterface
     */
    public function getQueueFacade(): QueueFacadeInterface
    {
        return $this->getProvidedDependency(QueueErrorCheckerDependencyProvider::QUEUE_FACADE);
    }
}
