<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales\Communication\Controller;

use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Sales\Communication\Controller\DetailController as SprykerDetailController;
use Spryker\Zed\Sales\SalesConfig;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\Sales\Communication\SalesCommunicationFactory getFactory()
 */
class DetailController extends SprykerDetailController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idSalesOrder = $this->castId($request->query->getInt(SalesConfig::PARAM_ID_SALES_ORDER));
        $orderTransfer = $this->getFacade()->findOrderByIdSalesOrder($idSalesOrder);
        if ($orderTransfer === null) {
            $this->addErrorMessage('Sales order #%d not found.', ['%d' => $idSalesOrder]);

            return $this->redirectResponse(Url::generate('/sales')->build());
        }
        $restrictedUserStore = $this->getFactory()->getUserFacade()->getCurrentUser()->getStoreName();
        if ($restrictedUserStore) {
            $orderStore = $orderTransfer->getStore();
            if ($restrictedUserStore !== $orderStore) {
                $this->addErrorMessage('Sales order #%d not found.', ['%d' => $idSalesOrder]);

                return $this->redirectResponse(Url::generate('/sales')->build());
            }
        }

        return parent::indexAction($request);
    }
}
