<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business;

use Pyz\Zed\Adyen\Business\Expander\OrderExpander;
use Pyz\Zed\Adyen\Business\Expander\OrderExpanderInterface;
use Pyz\Zed\Adyen\Business\Oms\Handler\RefundCommandHandler;
use Pyz\Zed\Adyen\Business\Oms\Handler\RefundCommandHandlerInterface;
use Pyz\Zed\Adyen\Business\Oms\Mapper\CaptureCommandMapper;
use Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapper;
use Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapperInterface;
use SprykerEco\Zed\Adyen\Business\AdyenBusinessFactory as SprykerEcoAdyenBusinessFactory;
use SprykerEco\Zed\Adyen\Business\Oms\Mapper\AdyenCommandMapperInterface;

/**
 * @method \Pyz\Zed\Adyen\AdyenConfig getConfig()
 * @method \SprykerEco\Zed\Adyen\Persistence\AdyenRepositoryInterface getRepository()
 * @method \SprykerEco\Zed\Adyen\Persistence\AdyenEntityManagerInterface getEntityManager()
 */
class AdyenBusinessFactory extends SprykerEcoAdyenBusinessFactory
{
    /**
     * @return \Pyz\Zed\Adyen\Business\Expander\OrderExpanderInterface
     */
    public function createOrderExpander(): OrderExpanderInterface
    {
        return new OrderExpander($this->getRepository());
    }

    /**
     * @return \Pyz\Zed\Adyen\Business\Oms\Handler\RefundCommandHandlerInterface
     */
    public function createPyzRefundCommandHandler(): RefundCommandHandlerInterface
    {
        return new RefundCommandHandler(
            $this->createPyzRefundCommandMapper(),
            $this->getAdyenApiFacade(),
            $this->createRefundCommandSaver()
        );
    }

    /**
     * @return \SprykerEco\Zed\Adyen\Business\Oms\Mapper\AdyenCommandMapperInterface
     */
    public function createCaptureCommandMapper(): AdyenCommandMapperInterface
    {
        return new CaptureCommandMapper(
            $this->createReader(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapperInterface
     */
    public function createPyzRefundCommandMapper(): RefundCommandMapperInterface
    {
        return  new RefundCommandMapper(
            $this->createReader(),
            $this->getConfig()
        );
    }
}
