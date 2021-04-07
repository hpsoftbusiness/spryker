<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Product\Business\Product\Variant;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\LocalizedAttributesBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Pyz\Zed\Product\Business\Product\Variant\VariantGenerator;
use Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface;
use Spryker\Zed\Product\Business\Product\Variant\VariantGeneratorInterface;
use Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Product
 * @group Business
 * @group Product
 * @group Variant
 * @group VariantGeneratorTest
 * Add your own group annotations below this line
 */
class VariantGeneratorTest extends Unit
{
    private const LOCALIZED_ATTRIBUTE_VALUE_PATTERN = '%s-%s';
    private const SUPER_ATTRIBUTE_KEY_LANG = 'lang';
    private const SUPER_ATTRIBUTE_LANG_VALUE_ENGLISH = 'English';
    private const SUPER_ATTRIBUTE_LANG_VALUE_RUSSIAN = 'Russian';

    /**
     * @var \PyzTest\Zed\Product\ProductBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $urlFacadeMock;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $skuGeneratorMock;

    /**
     * @var \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $productAttributeFacadeMock;

    /**
     * @var \Spryker\Zed\Product\Business\Product\Variant\VariantGeneratorInterface
     */
    private $sut;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->urlFacadeMock = $this->mockUrlFacadeBridge();
        $this->skuGeneratorMock = $this->mockSkuGenerator();
        $this->productAttributeFacadeMock = $this->mockProductAttributeFacade();
        $this->sut = $this->createVariantGenerator();
    }

    /**
     * @return void
     */
    public function testLocalizedSuperAttributeValuesAreSetForGeneratedConcreteProducts(): void
    {
        $productAbstractTransfer = $this->setupProductAbstract();
        $localesCollection = $this->getLocaleFacade()->getLocaleCollection();
        $productManagementAttributeTransfer = $this->createProductManagementAttributeTransfer(
            self::SUPER_ATTRIBUTE_KEY_LANG,
            [
                self::SUPER_ATTRIBUTE_LANG_VALUE_ENGLISH,
                self::SUPER_ATTRIBUTE_LANG_VALUE_RUSSIAN,
            ],
            $localesCollection
        );

        $this->productAttributeFacadeMock
            ->expects(self::once())
            ->method('findProductManagementAttributeByKey')
            ->willReturn($productManagementAttributeTransfer);

        $productConcreteTransfers = $this->sut->generate($productAbstractTransfer, [
            self::SUPER_ATTRIBUTE_KEY_LANG => [
                self::SUPER_ATTRIBUTE_LANG_VALUE_RUSSIAN,
                self::SUPER_ATTRIBUTE_LANG_VALUE_ENGLISH,
            ],
        ]);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $this->assertConcreteProductLocalizedSuperAttributes($productConcreteTransfer, $localesCollection);
        }
    }

    /**
     * @return void
     */
    public function testAttributesWithNullValuesAreSkipped(): void
    {
        $productAbstractTransfer = $this->setupProductAbstract();
        $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();
        $productManagementAttributeTransfer = $this->createProductManagementAttributeTransfer(
            self::SUPER_ATTRIBUTE_KEY_LANG,
            [
                self::SUPER_ATTRIBUTE_LANG_VALUE_RUSSIAN,
            ],
            [
                $localeTransfer,
            ]
        );
        $productManagementAttributeTransfer->getValues()[0]->getLocalizedValues()[0]->setTranslation(null);

        $this->productAttributeFacadeMock
            ->method('findProductManagementAttributeByKey')
            ->willReturn($productManagementAttributeTransfer);

        $productConcreteTransfers = $this->sut->generate($productAbstractTransfer, [
            self::SUPER_ATTRIBUTE_KEY_LANG => [
                self::SUPER_ATTRIBUTE_LANG_VALUE_RUSSIAN,
            ],
        ]);

        self::assertEmpty(
            $productConcreteTransfers[0]->getLocalizedAttributes()[$localeTransfer->getLocaleName()]->getAttributes()
        );
    }

    /**
     * @return void
     */
    public function testAttributesWithoutLocalizedValuesAreSkipped(): void
    {
        $productAbstractTransfer = $this->setupProductAbstract();
        $localeTransfer = $this->getLocaleFacade()->getCurrentLocale();
        $productManagementAttributeTransfer = $this->createProductManagementAttributeTransfer(
            self::SUPER_ATTRIBUTE_KEY_LANG,
            [
                self::SUPER_ATTRIBUTE_LANG_VALUE_RUSSIAN,
            ],
            [
                $localeTransfer,
            ]
        );
        $productManagementAttributeTransfer->getValues()[0]->setLocalizedValues(new ArrayObject());

        $this->productAttributeFacadeMock
            ->method('findProductManagementAttributeByKey')
            ->willReturn($productManagementAttributeTransfer);

        $productConcreteTransfers = $this->sut->generate($productAbstractTransfer, [
            self::SUPER_ATTRIBUTE_KEY_LANG => [
                self::SUPER_ATTRIBUTE_LANG_VALUE_RUSSIAN,
            ],
        ]);

        self::assertEmpty(
            $productConcreteTransfers[0]->getLocalizedAttributes()[$localeTransfer->getLocaleName()]->getAttributes()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $translatableLocales
     *
     * @return void
     */
    private function assertConcreteProductLocalizedSuperAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        array $translatableLocales
    ): void {
        self::assertCount(1, $productConcreteTransfer->getAttributes());
        $superAttributeValue = $productConcreteTransfer->getAttributes()[self::SUPER_ATTRIBUTE_KEY_LANG];

        foreach ($translatableLocales as $localeTransfer) {
            $localizedAttributes = $productConcreteTransfer->getLocalizedAttributes();

            self::assertEquals(
                $localizedAttributes[$localeTransfer->getLocaleName()][LocalizedAttributesTransfer::ATTRIBUTES][self::SUPER_ATTRIBUTE_KEY_LANG],
                $this->buildAttributeValueTranslation($superAttributeValue, $localeTransfer->getLocaleName())
            );
        }
    }

    /**
     * @param string $attributeKey
     * @param string[] $values
     * @param \Generated\Shared\Transfer\LocaleTransfer[] $translatableLocales
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    private function createProductManagementAttributeTransfer(
        string $attributeKey,
        array $values,
        array $translatableLocales
    ): ProductManagementAttributeTransfer {
        $productManagementAttributeTransfer = new ProductManagementAttributeTransfer();
        $productManagementAttributeTransfer->setKey($attributeKey);
        foreach ($values as $value) {
            $attributeValueTransfer = new ProductManagementAttributeValueTransfer();
            $attributeValueTransfer->setValue($value);

            foreach ($translatableLocales as $localeTransfer) {
                $attributeValueTranslationTransfer = new ProductManagementAttributeValueTranslationTransfer();
                $attributeValueTranslationTransfer->setFkLocale($localeTransfer->getIdLocale());
                $attributeValueTranslationTransfer->setTranslation(
                    $this->buildAttributeValueTranslation($value, $localeTransfer->getLocaleName())
                );

                $attributeValueTransfer->addLocalizedValue($attributeValueTranslationTransfer);
            }

            $productManagementAttributeTransfer->addValue($attributeValueTransfer);
        }

        return $productManagementAttributeTransfer;
    }

    /**
     * @param string $attributeValue
     * @param string $localeName
     *
     * @return string
     */
    private function buildAttributeValueTranslation(string $attributeValue, string $localeName): string
    {
        return sprintf(self::LOCALIZED_ATTRIBUTE_VALUE_PATTERN, $attributeValue, $localeName);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer
     */
    private function setupProductAbstract(): ProductAbstractTransfer
    {
        return $this->tester->haveProductAbstract([
            ProductAbstractTransfer::LOCALIZED_ATTRIBUTES => array_map(
                function (LocaleTransfer $localeTransfer): array {
                    return $this->buildLocalizedAttributesTransfer($localeTransfer)->toArray();
                },
                $this->getLocaleFacade()->getLocaleCollection()
            ),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return \Generated\Shared\Transfer\LocalizedAttributesTransfer
     */
    private function buildLocalizedAttributesTransfer(LocaleTransfer $localeTransfer): LocalizedAttributesTransfer
    {
        return (new LocalizedAttributesBuilder([
            LocalizedAttributesTransfer::LOCALE => $localeTransfer->toArray(),
        ]))->build();
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    private function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->tester->getLocator()->locale()->facade();
    }

    /**
     * @return \Spryker\Zed\Product\Dependency\Facade\ProductToUrlInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockUrlFacadeBridge(): ProductToUrlInterface
    {
        return $this->createMock(ProductToUrlInterface::class);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Sku\SkuGeneratorInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockSkuGenerator(): SkuGeneratorInterface
    {
        return $this->createMock(SkuGeneratorInterface::class);
    }

    /**
     * @return \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockProductAttributeFacade(): ProductAttributeFacadeInterface
    {
        return $this->createMock(ProductAttributeFacadeInterface::class);
    }

    /**
     * @return \Spryker\Zed\Product\Business\Product\Variant\VariantGeneratorInterface
     */
    private function createVariantGenerator(): VariantGeneratorInterface
    {
        return new VariantGenerator(
            $this->urlFacadeMock,
            $this->skuGeneratorMock,
            $this->productAttributeFacadeMock
        );
    }
}
