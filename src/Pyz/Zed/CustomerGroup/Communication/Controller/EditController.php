<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\CustomerGroup\Communication\Controller;

use Spryker\Zed\CustomerGroup\Communication\Controller\EditController as SprykerEditController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \Pyz\Zed\CustomerGroup\Communication\CustomerGroupCommunicationFactory getFactory()
 */
class EditController extends SprykerEditController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed[]|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $idCustomerGroup = $this->castId($request->query->get(static::PARAM_ID_CUSTOMER_GROUP));

        $dataProvider = $this->getFactory()->createCustomerGroupFormDataProvider();
        $form = $this->getFactory()
            ->createCustomerGroupForm(
                $dataProvider->getData($idCustomerGroup),
                $dataProvider->getOptions($idCustomerGroup)
            )
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var \Generated\Shared\Transfer\CustomerGroupTransfer $customerGroupTransfer */
            $customerGroupTransfer = $form->getData();

            $this->getFacade()->update($customerGroupTransfer);

            $this->addSuccessMessage(static::MESSAGE_CUSTOMER_GROUP_UPDATE_SUCCESS);

            return $this->redirectResponse(
                sprintf('/customer-group/view?%s=%d', static::PARAM_ID_CUSTOMER_GROUP, $idCustomerGroup)
            );
        }

        return $this->viewResponse([
            'form' => $form->createView(),
            'idCustomerGroup' => $idCustomerGroup,
            'customerGroupFormTabs' => $this->getFactory()->createCustomerGroupFormTabs()->createView(),
            'availableCustomerTable' => $this->getFactory()
                ->createAvailableCustomerTable($idCustomerGroup)
                ->render(),
            'assignedCustomerTable' => $this->getFactory()
                ->createAssignedCustomerTable($idCustomerGroup)
                ->render(),
            'availableProductListTable' => $this->getFactory()
                ->createAvailableProductListTable($idCustomerGroup)
                ->render(),
            'assignedProductListTable' => $this->getFactory()
                ->createAssignedProductListTable($idCustomerGroup)
                ->render(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function availableProductListTableAction(Request $request): JsonResponse
    {
        $idCustomerGroup = $this->castId($request->query->get(static::PARAM_ID_CUSTOMER_GROUP));
        $availableProductListTable = $this->getFactory()
            ->createAvailableProductListTable($idCustomerGroup);

        return $this->jsonResponse($availableProductListTable->fetchData());
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function assignedProductListTableAction(Request $request): JsonResponse
    {
        $idCustomerGroup = $this->castId($request->query->get(static::PARAM_ID_CUSTOMER_GROUP));
        $assignedProductListTable = $this->getFactory()
            ->createAssignedProductListTable($idCustomerGroup);

        return $this->jsonResponse($assignedProductListTable->fetchData());
    }
}
