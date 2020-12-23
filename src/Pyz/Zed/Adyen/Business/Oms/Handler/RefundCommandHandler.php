<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Adyen\Business\Oms\Handler;

use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\RefundTransfer;
use Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapperInterface;
use SprykerEco\Zed\Adyen\Business\Oms\Saver\AdyenCommandSaverInterface;
use SprykerEco\Zed\Adyen\Dependency\Facade\AdyenToAdyenApiFacadeInterface;

class RefundCommandHandler implements RefundCommandHandlerInterface
{
    /**
     * @var \Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapperInterface
     */
    protected $refundCommandMapper;

    /**
     * @var \SprykerEco\Zed\Adyen\Dependency\Facade\AdyenToAdyenApiFacadeInterface
     */
    protected $adyenApiFacade;

    /**
     * @var \SprykerEco\Zed\Adyen\Business\Oms\Saver\AdyenCommandSaverInterface
     */
    protected $saver;

    /**
     * @param \Pyz\Zed\Adyen\Business\Oms\Mapper\RefundCommandMapperInterface $refundCommandMapper
     * @param \SprykerEco\Zed\Adyen\Dependency\Facade\AdyenToAdyenApiFacadeInterface $adyenApiFacade
     * @param \SprykerEco\Zed\Adyen\Business\Oms\Saver\AdyenCommandSaverInterface $saver
     */
    public function __construct(
        RefundCommandMapperInterface $refundCommandMapper,
        AdyenToAdyenApiFacadeInterface $adyenApiFacade,
        AdyenCommandSaverInterface $saver
    ) {
        $this->refundCommandMapper = $refundCommandMapper;
        $this->adyenApiFacade = $adyenApiFacade;
        $this->saver = $saver;
    }

    /**
     * @param \Orm\Zed\Sales\Persistence\SpySalesOrderItem[] $orderItems
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\RefundTransfer $refundTransfer
     *
     * @return void
     */
    public function handle(array $orderItems, OrderTransfer $orderTransfer, RefundTransfer $refundTransfer): void
    {
        $request = $this->refundCommandMapper->buildRequestTransfer($orderTransfer, $refundTransfer);
        $response = $this->adyenApiFacade->performRefundApiCall($request);
        $this->saver->logResponse($request, $response);

        if ($response->getIsSuccess()) {
            $this->saver->save($orderItems);
        }
    }
}
