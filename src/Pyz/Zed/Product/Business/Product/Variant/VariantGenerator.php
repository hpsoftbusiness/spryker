<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Business\Product\Variant;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface;
use Spryker\Zed\Product\Business\Product\Variant\VariantGenerator as SprykerVariantGenerator;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;

class VariantGenerator extends SprykerVariantGenerator
{
    /**
     * @var \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    private $productAttributeFacade;

    /**
     * @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    private $productManagementAttributes = [];

    /**
     * @param \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface $urlFacade
     * @param \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface $skuGenerator
     * @param \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct(
        ProductToUrlInterface $urlFacade,
        SkuGeneratorInterface $skuGenerator,
        ProductAttributeFacadeInterface $productAttributeFacade
    ) {
        parent::__construct($urlFacade, $skuGenerator);

        $this->productAttributeFacade = $productAttributeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     * @param array $attributeTokens
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function createProductConcreteTransfer(
        ProductAbstractTransfer $productAbstractTransfer,
        array $attributeTokens
    ): ProductConcreteTransfer {
        $productConcreteTransfer = parent::createProductConcreteTransfer($productAbstractTransfer, $attributeTokens);

        $this->assignLocalizedSuperAttributeValues($productConcreteTransfer, $attributeTokens);

        return $productConcreteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array $attributeTokens
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    private function assignLocalizedSuperAttributeValues(
        ProductConcreteTransfer $productConcreteTransfer,
        array $attributeTokens
    ): ProductConcreteTransfer {
        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            $localeTransfer = $localizedAttributesTransfer->getLocale();
            $localizedAttributesTransfer->setAttributes(
                array_merge(
                    $localizedAttributesTransfer->getAttributes(),
                    $this->collectAttributeValuesByLocale($attributeTokens, $localeTransfer)
                )
            );
        }

        return $productConcreteTransfer;
    }

    /**
     * @param array $attributeTokens
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return array
     */
    private function collectAttributeValuesByLocale(array $attributeTokens, LocaleTransfer $localeTransfer): array
    {
        $localizedAttributes = [];

        foreach ($attributeTokens as $key => $value) {
            $attribute = $this->findProductManagementAttributeByKey($key);
            foreach ($attribute->getValues() as $attributeValueTransfer) {
                if ($attributeValueTransfer->getValue() !== $value) {
                    continue;
                }

                $attributeValueTranslation = $this->getAttributeValueTranslationByLocale($attributeValueTransfer, $localeTransfer);
                if (!$attributeValueTranslation) {
                    continue;
                }

                $localizedAttributes[$key] = $attributeValueTranslation;
            }
        }

        return $localizedAttributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer $attributeValueTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    private function getAttributeValueTranslationByLocale(
        ProductManagementAttributeValueTransfer $attributeValueTransfer,
        LocaleTransfer $localeTransfer
    ): ?string {
        foreach ($attributeValueTransfer->getLocalizedValues() as $attributeValueTranslationTransfer) {
            if ($attributeValueTranslationTransfer->getFkLocale() === $localeTransfer->getIdLocale()) {
                return $attributeValueTranslationTransfer->getTranslation();
            }
        }

        return null;
    }

    /**
     * @param string $key
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer|null
     */
    private function findProductManagementAttributeByKey(string $key): ?ProductManagementAttributeTransfer
    {
        if (isset($this->productManagementAttributes[$key])) {
            return $this->productManagementAttributes[$key];
        }

        $attribute = $this->productAttributeFacade->findProductManagementAttributeByKey($key);
        if (!$attribute) {
            return null;
        }

        $this->productManagementAttributes[$key] = $attribute;

        return $attribute;
    }
}
