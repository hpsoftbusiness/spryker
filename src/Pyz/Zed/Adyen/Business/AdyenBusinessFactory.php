<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business;

use Pyz\Zed\Adyen\Business\Expander\OrderExpander;
use Pyz\Zed\Adyen\Business\Expander\OrderExpanderInterface;
use Pyz\Zed\Adyen\Business\Hook\Mapper\MakePayment\CreditCardMapper;
use Pyz\Zed\Adyen\Business\Oms\Handler\RefundCommandHandler;
use Pyz\Zed\Adyen\Business\Oms\Handler\RefundCommandHandlerInterface;
use Pyz\Zed\Adyen\Business\Oms\Mapper\CaptureCommandMapper;
use Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapper;
use Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapperInterface;
use Pyz\Zed\Adyen\Business\Writer\AdyenWriter;
use SprykerEco\Zed\Adyen\Business\AdyenBusinessFactory as SprykerEcoAdyenBusinessFactory;
use SprykerEco\Zed\Adyen\Business\Hook\Mapper\MakePayment\AdyenMapperInterface;
use SprykerEco\Zed\Adyen\Business\Oms\Mapper\AdyenCommandMapperInterface;
use SprykerEco\Zed\Adyen\Business\Writer\AdyenWriterInterface;

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

    /**
     * @return \SprykerEco\Zed\Adyen\Business\Writer\AdyenWriterInterface
     */
    public function createWriter(): AdyenWriterInterface
    {
        return new AdyenWriter(
            $this->getEntityManager(),
            $this->getUtilEncodingService(),
            $this->getConfig()
        );
    }

    /**
     * @return \SprykerEco\Zed\Adyen\Business\Hook\Mapper\MakePayment\AdyenMapperInterface
     */
    public function createCreditCardMakePaymentMapper(): AdyenMapperInterface
    {
        return new CreditCardMapper($this->getConfig());
    }
}
