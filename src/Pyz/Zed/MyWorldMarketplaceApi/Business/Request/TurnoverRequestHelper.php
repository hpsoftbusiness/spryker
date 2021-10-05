<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldMarketplaceApi\Business\Request;

use Exception;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig;
use Spryker\Zed\Customer\Business\CustomerFacadeInterface;

class TurnoverRequestHelper implements TurnoverRequestHelperInterface
{
    /**
     * @var \Spryker\Zed\Customer\Business\CustomerFacadeInterface
     */
    protected $customerFacade;

    /**
     * @var \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig
     */
    protected $myWorldMarketplaceApiConfig;

    /**
     * @param \Spryker\Zed\Customer\Business\CustomerFacadeInterface $customerFacade
     * @param \Pyz\Zed\MyWorldMarketplaceApi\MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
     */
    public function __construct(
        CustomerFacadeInterface $customerFacade,
        MyWorldMarketplaceApiConfig $myWorldMarketplaceApiConfig
    ) {
        $this->customerFacade = $customerFacade;
        $this->myWorldMarketplaceApiConfig = $myWorldMarketplaceApiConfig;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Exception
     *
     * @return string
     */
    public function getCustomerId(OrderTransfer $orderTransfer): string
    {
        $customerTransfer = $this->customerFacade->findByReference($orderTransfer->getCustomerReference());

        if (!$customerTransfer) {
            throw new Exception('Customer not found.');
        }

        $customerId = $customerTransfer->getMyWorldCustomerId() ?? $customerTransfer->getMyWorldCustomerNumber();

        if (!$customerId) {
            throw new Exception('Customer ID or number required.');
        }

        return $customerId;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function getDealerId(OrderTransfer $orderTransfer): string
    {
        $dealerIdCountryMap = $this->myWorldMarketplaceApiConfig->getDealerIdCountryMap();
        $iso2Code = $orderTransfer->getCustomerCountryId();

        if (!isset($dealerIdCountryMap[$iso2Code])) {
            return $this->myWorldMarketplaceApiConfig->getDealerIdDefault();
        }

        return $dealerIdCountryMap[$iso2Code];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    public function getTurnoverReference(ItemTransfer $itemTransfer, OrderTransfer $orderTransfer): string
    {
        return sprintf(
            '%s-%s-%s-%s',
            $this->myWorldMarketplaceApiConfig->getOrderReferencePrefix(),
            $orderTransfer->getOrderReference(),
            $itemTransfer->getIdSalesOrderItem(),
            strtotime($orderTransfer->getCreatedAt())
        );
    }

    /**
     * @param int $orderItemId
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @throws \Exception
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function extractOrderItemFromOrderTransferById(
        int $orderItemId,
        OrderTransfer $orderTransfer
    ): ItemTransfer {
        /** @var \Generated\Shared\Transfer\ItemTransfer $item */
        foreach ($orderTransfer->getItems() as $item) {
            if ($item->getIdSalesOrderItem() === $orderItemId) {
                return $item;
            }
        }

        throw new Exception('Order item is not found in order');
    }
}
