<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Controller;

use Spryker\Zed\CustomerGroup\Communication\Controller\ViewController as SprykerViewController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class ViewController extends SprykerViewController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $idCustomerGroup = $request->get(static::PARAM_ID_CUSTOMER_GROUP);

        $customerGroupTransfer = $this->createCustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($idCustomerGroup);
        $customerGroupTransfer = $this->getFacade()
            ->get($customerGroupTransfer);

        $customerGroupArray = $customerGroupTransfer->toArray();

        $customerTable = $this->getFactory()->createCustomerTable($customerGroupTransfer);
        $productListTable = $this->getFactory()->createProductListTable($customerGroupTransfer);

        return $this->viewResponse([
            'customerGroup' => $customerGroupArray,
            'idCustomerGroup' => $idCustomerGroup,
            'customerTable' => $customerTable->render(),
            'productListTable' => $productListTable->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function tableProductListAction(Request $request): JsonResponse
    {
        $idCustomerGroup = $this->castId($request->query->getInt(static::PARAM_ID_CUSTOMER_GROUP));

        $customerGroupTransfer = $this->createCustomerGroupTransfer();
        $customerGroupTransfer->setIdCustomerGroup($idCustomerGroup);

        $table = $this->getFactory()->createProductListTable($customerGroupTransfer);

        return $this->jsonResponse($table->fetchData());
    }
}
