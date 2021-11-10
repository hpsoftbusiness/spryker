<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Weclapp\Client\SalesOrder;

use ArrayObject;
use DateTime;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\WeclappSalesOrderAddressTransfer;
use Generated\Shared\Transfer\WeclappSalesOrderEmailAddressesTransfer;
use Generated\Shared\Transfer\WeclappSalesOrderItemTransfer;
use Generated\Shared\Transfer\WeclappSalesOrderTransfer;
use Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap;

class SalesOrderMapper implements SalesOrderMapperInterface
{
    protected const PAYMENT_METHOD = 'Kreditkarte';
    protected const SALUTATION_MR = 'MR';
    protected const SALUTATION_MRS = 'MRS';

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappSalesOrderTransfer
     */
    public function mapOrderToWeclappSalesOrder(OrderTransfer $orderTransfer): WeclappSalesOrderTransfer
    {
        $weclappSalesOrderTransfer = new WeclappSalesOrderTransfer();
        $weclappSalesOrderTransfer->setCustomerNumber($orderTransfer->getCustomerReferenceOrFail())
            ->setOrderNumber($orderTransfer->getOrderReferenceOrFail())
            ->setOrderDate((int)((new DateTime($orderTransfer->getCreatedAtOrFail()))->format('Uv')))
            ->setRecordCurrencyName($orderTransfer->getCurrencyIsoCode())
            ->setPaymentMethodName(static::PAYMENT_METHOD)
            ->setCommercialLanguage($this->mapToWeclappCommercialLanguage($orderTransfer))
            ->setOrderItems($this->mapToWeclappOrderItems($orderTransfer))
            ->setInvoiceAddress($this->mapToWeclappSalesOrderAddress($orderTransfer->getBillingAddressOrFail()))
            ->setSalesInvoiceEmailAddresses($this->mapToWeclappSalesOrderEmailAddresses($orderTransfer->getBillingAddressOrFail()))
            ->setDeliveryAddress($this->mapToWeclappSalesOrderAddress($orderTransfer->getShippingAddressOrFail()))
            ->setDeliveryEmailAddresses($this->mapToWeclappSalesOrderEmailAddresses($orderTransfer->getShippingAddressOrFail()));

        return $weclappSalesOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string
     */
    protected function mapToWeclappCommercialLanguage(OrderTransfer $orderTransfer): string
    {
        $localeName = $orderTransfer->getLocaleOrFail()->getLocaleNameOrFail();

        return strtok($localeName, '_');
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return \ArrayObject
     */
    protected function mapToWeclappOrderItems(OrderTransfer $orderTransfer): ArrayObject
    {
        $weclappOrderItems = new ArrayObject();
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $weclappSalesOrderItemTransfer = new WeclappSalesOrderItemTransfer();
            $weclappSalesOrderItemTransfer->setArticleNumber($itemTransfer->getSkuOrFail())
                ->setQuantity((string)$itemTransfer->getQuantityOrFail())
                ->setManualUnitPrice(true)
                ->setUnitPrice((string)($itemTransfer->getUnitPriceToPayAggregationOrFail() / 100))
                ->setTaxId($itemTransfer->getIdWeclappTaxOrFail());

            $weclappOrderItems[] = $weclappSalesOrderItemTransfer;
        }

        return $weclappOrderItems;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappSalesOrderAddressTransfer
     */
    protected function mapToWeclappSalesOrderAddress(AddressTransfer $addressTransfer): WeclappSalesOrderAddressTransfer
    {
        $weclappInvoiceAddress = new WeclappSalesOrderAddressTransfer();
        $weclappInvoiceAddress->setCountryCode($addressTransfer->getCountryOrFail()->getIso2Code())
            ->setCompany($addressTransfer->getCompany())
            ->setSalutation($this->mapToWeclappSalutation($addressTransfer->getSalutation()))
            ->setFirstName($addressTransfer->getFirstName())
            ->setLastName($addressTransfer->getLastName())
            ->setStreet1($addressTransfer->getAddress1())
            ->setStreet2($addressTransfer->getAddress2())
            ->setCity($addressTransfer->getCity())
            ->setZipCode($addressTransfer->getZipCode())
            ->setPhoneNumber($addressTransfer->getPhone());

        return $weclappInvoiceAddress;
    }

    /**
     * @param string|null $salutation
     *
     * @return string|null
     */
    protected function mapToWeclappSalutation(?string $salutation): ?string
    {
        switch ($salutation) {
            case SpyCustomerTableMap::COL_SALUTATION_MR:
            case SpyCustomerTableMap::COL_SALUTATION_DR:
                return static::SALUTATION_MR;
            case SpyCustomerTableMap::COL_SALUTATION_MS:
            case SpyCustomerTableMap::COL_SALUTATION_MRS:
                return static::SALUTATION_MRS;
            default:
                return null;
        }
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\WeclappSalesOrderEmailAddressesTransfer
     */
    protected function mapToWeclappSalesOrderEmailAddresses(AddressTransfer $addressTransfer): WeclappSalesOrderEmailAddressesTransfer
    {
        $weclappSalesOrderEmailAddressesTransfer = new WeclappSalesOrderEmailAddressesTransfer();
        $weclappSalesOrderEmailAddressesTransfer->setToAddresses($addressTransfer->getEmail());

        return $weclappSalesOrderEmailAddressesTransfer;
    }
}
