<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PriceProduct;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Zed\PriceProduct\PriceProductConfig as SprykerPriceProductConfig;

/**
 * @method \Pyz\Shared\PriceProduct\PriceProductConfig getSharedConfig()
 */
class PriceProductConfig extends SprykerPriceProductConfig
{
    /**
     * Perform orphan prices removing automatically.
     */
    protected const IS_DELETE_ORPHAN_STORE_PRICES_ON_SAVE_ENABLED = false;

    /**
     * @return string
     */
    public function getPriceTypeBenefitName(): string
    {
        return $this->getSharedConfig()->getPriceTypeBenefitName();
    }

    /**
     * @return string
     */
    public function getPriceTypeOriginalName(): string
    {
        return $this->getSharedConfig()->getPriceTypeOriginalName();
    }

    /**
     * @return string
     */
    public function getProductAttributeKeyBenefitSalesPrice(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_BENEFIT_STORE_SALES_PRICE);
    }
}
