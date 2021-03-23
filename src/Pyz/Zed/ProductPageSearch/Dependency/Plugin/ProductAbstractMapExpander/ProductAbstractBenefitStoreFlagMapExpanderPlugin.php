<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductPageSearch\Dependency\Plugin\ProductAbstractMapExpander;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\PageMapTransfer;
use Generated\Shared\Transfer\ProductPageSearchTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductPageSearchExtension\Dependency\PageMapBuilderInterface;
use Spryker\Zed\ProductPageSearchExtension\Dependency\Plugin\ProductAbstractMapExpanderPluginInterface;

class ProductAbstractBenefitStoreFlagMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    private const ATTRIBUTE_BENEFIT_STORE = 'benefit_store';
    private const ATTRIBUTE_SHOPPING_POINT_STORE = 'shopping_point_store';

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
        $pageMapTransfer->setBenefitStore(
            $this->getAttributeValue($productData, self::ATTRIBUTE_BENEFIT_STORE)
        );

        $pageMapTransfer->setShoppingPointStore(
            $this->getAttributeValue($productData, self::ATTRIBUTE_SHOPPING_POINT_STORE)
        );

        return $pageMapTransfer;
    }

    /**
     * @param array $productData
     * @param string $attributeKey
     *
     * @return bool
     */
    private function getAttributeValue(array $productData, string $attributeKey): bool
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
}
