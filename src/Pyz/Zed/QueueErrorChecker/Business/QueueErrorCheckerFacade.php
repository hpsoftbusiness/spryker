<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\QueueErrorChecker\Business;

use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Pyz\Zed\QueueErrorChecker\Business\QueueErrorCheckerBusinessFactory getFactory()
 */
class QueueErrorCheckerFacade extends AbstractFacade implements QueueErrorCheckerFacadeInterface
{
    /**
     * @param string $queueName
     * @param int $limit
     *
     * @return array
     */
    public function getQueueErrorMessages(string $queueName, int $limit): array
    {
        return $this->getFactory()->createQueueErrorMessage()->getMessages($queueName, $limit);
    }
}
