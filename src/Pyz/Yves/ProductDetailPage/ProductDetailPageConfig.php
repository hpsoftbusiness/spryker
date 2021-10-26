<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductDetailPage;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Pyz\Shared\ProductDetailPage\ProductDetailPageConstants;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class ProductDetailPageConfig extends AbstractBundleConfig
{
    /**
     * @return string
     */
    public function getShoppingPointsAmountAttributeName(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_AMOUNT);
    }

    /**
     * @return string
     */
    public function getProductAttributeKeyShoppingPointsStore(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_STORE);
    }

    /**
     * @return string
     */
    public function getProductAttributeKeyBenefitSalesPrice(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE_SALES_PRICE);
    }

    /**
     * @return string
     */
    public function getProductAttributeKeyBenefitStore(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE);
    }

    /**
     * @return string
     */
    public function getProductAttributeKeyBenefitAmount(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_AMOUNT);
    }

    /**
     * TODO: removed after refactoring default shipment price
     *
     * @return float
     */
    public function getDefaultShipmentPrice(): float
    {
        $defaultsShipmentPricesPerStore = $this->get(ProductDetailPageConstants::DEFAULT_SHIPMENT_PRICE, []);

        return $defaultsShipmentPricesPerStore[Store::getInstance()->getStorePrefix()] ?? 4.95;
    }
}
