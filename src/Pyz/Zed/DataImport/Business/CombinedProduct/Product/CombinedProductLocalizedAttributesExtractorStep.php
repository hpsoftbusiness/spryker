<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\DataImport\Business\CombinedProduct\Product;

use Pyz\Zed\DataImport\Business\Model\Product\ProductLocalizedAttributesExtractorStep;
use Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface;

class CombinedProductLocalizedAttributesExtractorStep extends ProductLocalizedAttributesExtractorStep
{
    /**
     * @param \Pyz\Zed\DataImport\Business\Model\Product\Repository\ProductRepositoryInterface $productRepository
     * @param array $defaultAttributes
     */
    public function __construct(ProductRepositoryInterface $productRepository, array $defaultAttributes = [])
    {
        parent::__construct($productRepository, $defaultAttributes);
    }

    /**
     * @return string
     */
    protected function getAttributeKeyPrefix(): string
    {
        return 'product.attribute_key_';
    }

    /**
     * @return string
     */
    protected function getAttributeValuePrefix(): string
    {
        return 'product.value_';
    }
}
