<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductCartConnector;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Zed\ProductCartConnector\ProductCartConnectorConfig as SprykerProductCartConnectorConfig;

class ProductCartConnectorConfig extends SprykerProductCartConnectorConfig
{
    /**
     * @return string
     */
    public function getShoppingPointStoreAttributeName(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_STORE);
    }

    /**
     * @return string
     */
    public function getShoppingPointsAmountAttributeName(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS);
    }

    /**
     * @return string
     */
    public function getBenefitVoucherSalesPriceAttributeName(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE_SALES_PRICE);
    }

    /**
     * @return string
     */
    public function getBenefitVoucherStoreAttributeName(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE);
    }

    /**
     * @return string
     */
    public function getBenefitVoucherAmountAttributeName(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_AMOUNT);
    }
}
