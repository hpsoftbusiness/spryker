<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Refund\Communication\Controller;

use Spryker\Zed\Refund\Communication\Controller\SalesController as SprykerSalesController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Spryker\Zed\Refund\Communication\RefundCommunicationFactory getFactory()
 * @method \Spryker\Zed\Refund\Persistence\RefundQueryContainerInterface getQueryContainer()
 * @method \Pyz\Zed\Refund\Business\RefundFacadeInterface getFacade()
 */
class SalesController extends SprykerSalesController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function detailsAction(Request $request): array
    {
        $orderTransfer = $this->getOrderTransfer($request);
        $refundDetailsCollection = $this->getFacade()->getRefundDetailsByIdSalesOrder($orderTransfer->getIdSalesOrder());

        return $this->viewResponse([
            'refundDetailsCollections' => $refundDetailsCollection,
            'order' => $orderTransfer,
        ]);
    }
}
