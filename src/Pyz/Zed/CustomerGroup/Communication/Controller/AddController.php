<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Controller;

use Spryker\Zed\CustomerGroup\Communication\Controller\AddController as SprykerAddController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class AddController extends SprykerAddController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed[]|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $dataProvider = $this->getFactory()->createCustomerGroupFormDataProvider();

        $form = $this->getFactory()
            ->createCustomerGroupForm(
                $dataProvider->getData(),
                $dataProvider->getOptions()
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer */
            $customerGroupTransfer = $form->getData();

            $this->getFacade()->add($customerGroupTransfer);

            $this->addSuccessMessage(static::MESSAGE_CUSTOMER_GROUP_CREATE_SUCCESS);

            return $this->redirectResponse('/customer-group');
        }

        return $this->viewResponse([
            'customerGroupFormTabs' => $this->getFactory()->createCustomerGroupFormTabs()->createView(),
            'form' => $form->createView(),
            'availableCustomerTable' => $this->getFactory()
                ->createAvailableCustomerTable()
                ->render(),
            'assignedCustomerTable' => $this->getFactory()
                ->createAssignedCustomerTable()
                ->render(),
            'availableProductListTable' => $this->getFactory()
                ->createAvailableProductListTable()
                ->render(),
            'assignedProductListTable' => $this->getFactory()
                ->createAssignedProductListTable()
                ->render(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableProductListTableAction(): JsonResponse
    {
        $availableProductListTable = $this->getFactory()
            ->createAvailableProductListTable();

        return $this->jsonResponse($availableProductListTable->fetchData());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedProductListTableAction(): JsonResponse
    {
        $assignedProductListTable = $this->getFactory()
            ->createAssignedProductListTable();

        return $this->jsonResponse($assignedProductListTable->fetchData());
    }
}
