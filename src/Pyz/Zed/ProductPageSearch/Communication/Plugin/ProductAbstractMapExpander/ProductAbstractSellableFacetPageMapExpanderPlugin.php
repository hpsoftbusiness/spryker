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
 * @method \Pyz\Zed\ProductPageSearch\Communication\ProductPageSearchCommunicationFactory getFactory()
 */
class ProductAbstractSellableFacetPageMapExpanderPlugin extends AbstractPlugin implements ProductAbstractMapExpanderPluginInterface
{
    private const ATTRIBUTE_SELLABLE_PREFIX = 'sellable_';

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
        $productAttributes = $productData[ProductPageSearchTransfer::ATTRIBUTES] ?? [];
        $sellableCountries = $this->collectSellableCountries($productAttributes);
        $pageMapBuilder->addStringFacet($pageMapTransfer, $this->getName(), $sellableCountries);

        return $pageMapTransfer;
    }

    /**
     * @param array $productAttributes
     *
     * @return string[]
     */
    private function collectSellableCountries(array $productAttributes): array
    {
        $sellableCountries = [];

        foreach ($productAttributes as $key => $value) {
            if (strpos($key, self::ATTRIBUTE_SELLABLE_PREFIX) === false) {
                continue;
            }

            $singularValue = $this->extractSingularAttributeValue($value);
            if (!$singularValue) {
                continue;
            }

            $sellableCountries[] = $this->extractCountryCode($key);
        }

        return $sellableCountries;
    }

    /**
     * @return string
     */
    private function getName(): string
    {
        return $this->getConfig()->getProductAbstractSellableFacetName();
    }

    /**
     * @param string $attributeKey
     *
     * @return string
     */
    private function extractCountryCode(string $attributeKey): string
    {
        return str_replace(self::ATTRIBUTE_SELLABLE_PREFIX, '', $attributeKey);
    }

    /**
     * @param mixed $attributeValue
     *
     * @return bool
     */
    private function extractSingularAttributeValue($attributeValue): bool
    {
        if (!is_array($attributeValue)) {
            return (bool)$attributeValue;
        }

        if (!count($attributeValue)) {
            return false;
        }

        return (bool)current($attributeValue);
    }
}
