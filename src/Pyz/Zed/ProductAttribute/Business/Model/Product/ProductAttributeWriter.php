<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttribute\Business\Model\Product;

use Generated\Shared\Transfer\ProductAttributeKeyTransfer;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeWriter as SprykerProductAttributeWriter;
use Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface;
use Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface;
use Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilSanitizeXssServiceInterface;

class ProductAttributeWriter extends SprykerProductAttributeWriter
{
    /**
     * @var \Pyz\Zed\ProductAttribute\Dependency\Plugin\ProductAttributePreSaveCheckPluginInterface[]
     */
    private $productAttributePreSaveCheckPlugins;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductAttributeReaderInterface $reader
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToLocaleInterface $localeFacade
     * @param \Spryker\Zed\ProductAttribute\Dependency\Facade\ProductAttributeToProductInterface $productFacade
     * @param \Spryker\Zed\ProductAttribute\Business\Model\Product\ProductReaderInterface $productReader
     * @param \Spryker\Zed\ProductAttribute\Dependency\Service\ProductAttributeToUtilSanitizeXssServiceInterface $utilSanitizeXssService
     * @param \Pyz\Zed\ProductAttribute\Dependency\Plugin\ProductAttributePreSaveCheckPluginInterface[] $productAttributePreSaveCheckPlugins
     */
    public function __construct(
        ProductAttributeReaderInterface $reader,
        ProductAttributeToLocaleInterface $localeFacade,
        ProductAttributeToProductInterface $productFacade,
        ProductReaderInterface $productReader,
        ProductAttributeToUtilSanitizeXssServiceInterface $utilSanitizeXssService,
        array $productAttributePreSaveCheckPlugins
    ) {
        parent::__construct($reader, $localeFacade, $productFacade, $productReader, $utilSanitizeXssService);

        $this->productAttributePreSaveCheckPlugins = $productAttributePreSaveCheckPlugins;
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getAttributesDataToSave(array $attributes): array
    {
        $attributeData = [];

        foreach ($attributes as $attribute) {
            $key = $attribute[ProductAttributeKeyTransfer::KEY];
            // TODO: We are going to get rid of customer_groups (https://spryker.atlassian.net/browse/MYW-1358)
            if (substr($key, 0, 15) === 'customer_group_') {
                continue;
            }
            $localeCode = $attribute['locale_code'];
            $value = $attribute['value'];

            if (!is_bool($value)
                && !is_numeric($value)
                && is_string($value)
            ) {
                $value = $this->sanitizeString($attribute['value']);
            }

            if ($value === '') {
                continue;
            }

            $attributeData[$localeCode][$key] = $value;
        }

        $this->checkProductAttributes($attributeData);

        return $attributeData;
    }

    /**
     * @param array $attributes
     *
     * @return void
     */
    private function checkProductAttributes(array $attributes): void
    {
        foreach ($this->productAttributePreSaveCheckPlugins as $attributePreSaveCheckPlugin) {
            $attributePreSaveCheckPlugin->check($attributes);
        }
    }
}
