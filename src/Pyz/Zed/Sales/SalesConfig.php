<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Shared\Adyen\AdyenConfig;
use Pyz\Shared\DummyPrepayment\DummyPrepaymentConfig;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Sales\SalesConstants;
use Spryker\Zed\Sales\SalesConfig as SprykerSalesConfig;

class SalesConfig extends SprykerSalesConfig
{
    public const ORDER_REFERENCE_PREFIX_NUMBER = 1279;

    /**
     * Specification:
     * - Defines priority of the payment methods that are supposed to be considered as main payment type for the order.
     */
    public const MAIN_PAYMENT_METHOD_PRIORITY_LIST = [
        AdyenConfig::ADYEN_CREDIT_CARD,
        DummyPrepaymentConfig::DUMMY_PREPAYMENT,
        MyWorldPaymentConfig::PAYMENT_METHOD_CASHBACK_NAME,
        MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_NAME,
        MyWorldPaymentConfig::PAYMENT_METHOD_EVOUCHER_ON_BEHALF_OF_MARKETER_NAME,
    ];

    /**
     * Defines the prefix for the sequence number which is the public id of an order.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\SequenceNumberSettingsTransfer
     */
    public function getOrderReferenceDefaults()
    {
        $sequenceNumberSettingsTransfer = parent::getOrderReferenceDefaults();

        $sequenceNumberPrefixParts = [];
        $sequenceNumberPrefixParts[] = Store::getInstance()->getStoreName();
        $sequenceNumberPrefixParts[] = $this->get(SalesConstants::ENVIRONMENT_PREFIX, '');
        $sequenceNumberPrefixParts[] = static::ORDER_REFERENCE_PREFIX_NUMBER;

        $prefix = implode($this->getUniqueIdentifierSeparator(), $sequenceNumberPrefixParts);

        return $sequenceNumberSettingsTransfer->setPrefix($prefix);
    }

    /**
     * This method determines state machine process from the given quote transfer and order item.
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return string
     */
    public function determineProcessForOrderItem(QuoteTransfer $quoteTransfer, ItemTransfer $itemTransfer)
    {
        $paymentMethodStatemachineMapping = $this->getPaymentMethodStatemachineMapping();

        if (!array_key_exists($quoteTransfer->getPayment()->getPaymentSelection(), $paymentMethodStatemachineMapping)) {
            return parent::determineProcessForOrderItem($quoteTransfer, $itemTransfer);
        }

        return $paymentMethodStatemachineMapping[$quoteTransfer->getPayment()->getPaymentSelection()];
    }

    /**
     * This method provides list of urls to render blocks inside order detail page.
     * URL defines path to external bundle controller. For example: /discount/sales/list would call discount bundle, sales controller, list action.
     * Action should return return array or redirect response.
     *
     * example:
     * [
     *    'discount' => '/discount/sales/index',
     * ]
     *
     * @return array
     */
    public function getSalesDetailExternalBlocksUrls()
    {
        $projectExternalBlocks = [
            'cart_note' => '/cart-note/sales/list', #CartNoteFeature
            'return' => '/sales-return-gui/sales/list',
            'cart_note_bundle_items' => '/cart-note-product-bundle-connector/sales/list', #CartNoteFeature
            'payments' => '/payment/sales/list',
            'giftCards' => '/gift-card/sales/list',
            'shipment' => '/shipment/sales/list',
            'discount' => '/discount/sales/list',
            'refund' => '/refund/sales/list',
            'refund_details' => '/refund/sales/details',
        ];

        $externalBlocks = parent::getSalesDetailExternalBlocksUrls();

        return array_merge($externalBlocks, $projectExternalBlocks);
    }

    /**
     * @api
     *
     * @return bool
     */
    public function isHydrateOrderHistoryToItems(): bool
    {
        return false;
    }
}
