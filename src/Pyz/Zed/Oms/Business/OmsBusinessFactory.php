<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Oms\Business;

use Pyz\Zed\Oms\Business\Calculator\InitiationTimeoutCalculator;
use Pyz\Zed\Oms\Business\Calculator\TimeoutProcessorTimeoutCalculatorInterface;
use Pyz\Zed\Oms\Business\Mail\MailHandler;
use Spryker\Shared\Log\LoggerTrait;
use Spryker\Zed\Oms\Business\OmsBusinessFactory as SprykerOmsBusinessFactory;

class OmsBusinessFactory extends SprykerOmsBusinessFactory
{
    use LoggerTrait;

    /**
     * @return \Pyz\Zed\Oms\Business\Calculator\TimeoutProcessorTimeoutCalculatorInterface
     */
    public function createInitiationTimeoutCalculator(): TimeoutProcessorTimeoutCalculatorInterface
    {
        return new InitiationTimeoutCalculator();
    }

    /**
     * @return \Pyz\Zed\Oms\Business\Mail\MailHandler
     */
    public function createMailHandler(): MailHandler
    {
        return new MailHandler(
            $this->getSalesFacade(),
            $this->getMailFacade(),
            $this->getOmsOrderMailExpanderPlugins(),
            $this->getLogger()
        );
    }
}
