<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Communication\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

/**
 * @method \Pyz\Zed\ProductPageSearch\ProductPageSearchConfig getConfig()
 */
class ProductAbstractBenefitStoreFlagMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    private const ATTRIBUTE_BENEFIT_STORE = 'benefit_store';
    private const ATTRIBUTE_SHOPPING_POINT_STORE = 'shopping_point_store';
    private const ATTRIBUTE_SHOPPING_POINTS = 'shopping_points';
    private const ATTRIBUTE_CASHBACK_AMOUNT = 'cashback_amount';

    /**
     * Necessary for handling attributes with string values but boolean meaning. Once import is fixed, this can be removed.
     */
    private const STRING_TRUE_VALUES = [
        'TRUE',
    ];

    /**
     * @param \Generated\Shared\Transfer\PageMapTransfer $pageMapTransfer
     * @param \Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface $pageMapBuilder
     * @param array $productData
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\PageMapTransfer
     */
    public function expandProductMap(
        PageMapTransfer $pageMapTransfer,
        PageMapBuilderInterface $pageMapBuilder,
        array $productData,
        LocaleTransfer $localeTransfer
    ): PageMapTransfer {
        $pageMapTransfer->setBenefitStore($this->getBenefitStoreFlagValue($productData));
        $pageMapTransfer->setShoppingPointStore($this->getShoppingPointStoreFlagValue($productData));

        return $pageMapTransfer;
    }

    /**
     * @param array $productData
     *
     * @return bool
     */
    private function getBenefitStoreFlagValue(array $productData): bool
    {
        $benefitStoreValue = $this->getBooleanAttributeValue($productData, self::ATTRIBUTE_BENEFIT_STORE);
        if (!$benefitStoreValue) {
            return false;
        }

        return $this->assertAttributeHasValue($productData, self::ATTRIBUTE_CASHBACK_AMOUNT);
    }

    /**
     * @param array $productData
     *
     * @return bool
     */
    private function getShoppingPointStoreFlagValue(array $productData): bool
    {
        $benefitStoreValue = $this->getBooleanAttributeValue($productData, self::ATTRIBUTE_SHOPPING_POINT_STORE);
        if (!$benefitStoreValue) {
            return false;
        }

        return $this->assertAttributeHasValue($productData, self::ATTRIBUTE_SHOPPING_POINTS);
    }

    /**
     * @param array $productData
     * @param string $attributeKey
     *
     * @return bool
     */
    private function getBooleanAttributeValue(array $productData, string $attributeKey): bool
    {
        $value = $productData[ProductPageSearchTransfer::ATTRIBUTES][$attributeKey] ?? false;
        if (is_array($value)) {
            $value = current($value);
        }

        if (is_string($value)) {
            $value = in_array(strtoupper($value), self::STRING_TRUE_VALUES, true);
        }

        return (bool)$value;
    }

    /**
     * @param array $productData
     * @param string $attributeName
     *
     * @return bool
     */
    private function assertAttributeHasValue(array $productData, string $attributeName): bool
    {
        $attributeValue = $productData[ProductPageSearchTransfer::ATTRIBUTES][$attributeName] ?? null;
        if (!is_array($attributeValue)) {
            return !empty($attributeValue);
        }

        if (!count($attributeValue)) {
            return false;
        }

        return !empty((int)current($attributeValue));
    }
}
