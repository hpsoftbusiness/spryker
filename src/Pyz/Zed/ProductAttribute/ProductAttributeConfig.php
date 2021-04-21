<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Zed\ProductAttribute\ProductAttributeConfig as SprykerProductAttributeConfig;

class ProductAttributeConfig extends SprykerProductAttributeConfig
{
    /**
     * @api
     *
     * @return array
     */
    public function getAttributeAvailableTypes()
    {
        return [
            'text' => 'text',
            'textarea' => 'textarea',
            'number' => 'number',
            'float' => 'float',
            'date' => 'date',
            'time' => 'time',
            'datetime' => 'datetime',
            'select' => 'select',
            'checkbox' => 'checkbox',
        ];
    }

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
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_AMOUNT);
    }
}
