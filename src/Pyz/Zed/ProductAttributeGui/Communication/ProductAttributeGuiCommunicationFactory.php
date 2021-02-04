<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeGui\Communication;

use Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider;
use Spryker\Zed\ProductAttributeGui\Communication\ProductAttributeGuiCommunicationFactory as SprykerProductAttributeGuiCommunicationFactory;

class ProductAttributeGuiCommunicationFactory extends SprykerProductAttributeGuiCommunicationFactory
{
    /**
     * @return \Pyz\Zed\ProductAttributeGui\Communication\Form\DataProvider\AttributeTranslationFormCollectionDataProvider
     */
    public function createAttributeTranslationFormCollectionDataProvider(): AttributeTranslationFormCollectionDataProvider
    {
        return new AttributeTranslationFormCollectionDataProvider(
            $this->getProductAttributeFacade(),
            $this->getProductAttributeQueryContainer(),
            $this->getLocaleFacade()
        );
    }
}
