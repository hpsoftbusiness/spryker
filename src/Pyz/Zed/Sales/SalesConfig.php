<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sales;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\Sales\SalesConfig as SprykerSalesConfig;

class SalesConfig extends SprykerSalesConfig
{
    public const ORDER_REFERENCE_PREFIX_NUMBER = 1279;

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
        $prefix = $sequenceNumberSettingsTransfer->getPrefix() . static::ORDER_REFERENCE_PREFIX_NUMBER;
        $sequenceNumberSettingsTransfer->setPrefix($prefix);

        return $sequenceNumberSettingsTransfer;
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
