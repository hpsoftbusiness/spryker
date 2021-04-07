<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Zed\Product\Communication\Plugin\Product\DataHealer;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\LocalizedAttributesBuilder;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\LocalizedAttributesTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes;
use Orm\Zed\Product\Persistence\SpyProductLocalizedAttributesQuery;
use Pyz\Zed\Event\Business\EventFacade;
use Pyz\Zed\Product\Communication\Plugin\Product\DataHealer\SuperAttributeLocalizedValuesHealerPlugin;
use Pyz\Zed\Product\ProductDependencyProvider;
use Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Zed
 * @group Product
 * @group Communication
 * @group Plugin
 * @group Product
 * @group DataHealer
 * @group SuperAttributeLocalizedValuesHealerPluginTest
 * Add your own group annotations below this line
 */
class SuperAttributeLocalizedValuesHealerPluginTest extends Unit
{
    private const LOCALE_DE = 'de_DE';
    private const LOCALE_ENG = 'en_US';

    private const ATTRIBUTE_KEY_LANG = 'lang';
    private const ATTRIBUTE_KEY_SIZE = 'size';

    /**
     * @var \PyzTest\Zed\Product\ProductCommunicationTester
     */
    protected $tester;

    /**
     * @var \Pyz\Zed\Product\Communication\Plugin\Product\DataHealer\SuperAttributeLocalizedValuesHealerPlugin
     */
    private $sut;

    /**
     * @var \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $productAttributeFacadeMock;

    /**
     * @var \Spryker\Zed\Event\Business\EventFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $eventFacadeMock;

    /**
     * @return void
     */
    protected function _before(): void
    {
        $this->productAttributeFacadeMock = $this->mockProductAttributeFacade();
        $this->tester->setDependency(
            ProductDependencyProvider::FACADE_PRODUCT_ATTRIBUTE,
            $this->productAttributeFacadeMock
        );
        $this->eventFacadeMock = $this->mockEventFacade();
        $this->tester->setDependency(
            ProductDependencyProvider::FACADE_EVENT,
            $this->eventFacadeMock
        );

        $this->sut = new SuperAttributeLocalizedValuesHealerPlugin();
    }

    /**
     * @return void
     */
    public function testLocalizedSuperAttributeValuesHealerTranslatesSuperAttributeValues(): void
    {
        $deLocaleTransfer = $this->setupLocale(self::LOCALE_DE);
        $engLocaleTransfer = $this->setupLocale(self::LOCALE_ENG);
        $productManagementAttributeLanguage = $this->createProductManagementAttributeTransfer(
            self::ATTRIBUTE_KEY_LANG,
            [
                'English' => [
                    $deLocaleTransfer->getIdLocale() => 'Englisch',
                    $engLocaleTransfer->getIdLocale() => 'English',
                ],
                'German' => [
                    $deLocaleTransfer->getIdLocale() => 'Deutsch',
                    $engLocaleTransfer->getIdLocale() => 'German',
                ],
                'Russian' => [
                    $deLocaleTransfer->getIdLocale() => 'Russisch',
                    $engLocaleTransfer->getIdLocale() => 'Russian',
                ],
            ]
        );
        $productManagementAttributeSize = $this->createProductManagementAttributeTransfer(
            self::ATTRIBUTE_KEY_SIZE,
            [
                'S' => [],
                'M' => [],
                'L' => [],
            ]
        );

        $this->productAttributeFacadeMock
            ->expects(self::once())
            ->method('getProductSuperAttributeCollection')
            ->willReturn([
                $productManagementAttributeLanguage,
                $productManagementAttributeSize,
            ]);

        $productConcrete1 = $this->setupProduct([
            self::ATTRIBUTE_KEY_LANG => 'German',
            self::ATTRIBUTE_KEY_SIZE => 'L',
        ]);

        $productConcrete2 = $this->setupProduct([
            self::ATTRIBUTE_KEY_LANG => 'Russian',
        ]);

        $productConcrete3 = $this->setupProduct([
            self::ATTRIBUTE_KEY_LANG => 'Lithuanian',
        ]);

        $productConcrete4 = $this->setupProduct([
            self::ATTRIBUTE_KEY_SIZE => 'S',
        ]);

        $this->eventFacadeMock
            ->expects(self::exactly(2))
            ->method('triggerBulk');

        $this->sut->execute();

        $this->assertProductLocalizedAttributes(
            $productConcrete1,
            [
                self::LOCALE_DE => [
                    self::ATTRIBUTE_KEY_LANG => 'Deutsch',
                ],
                self::LOCALE_ENG => [
                    self::ATTRIBUTE_KEY_LANG => 'German',
                ],
            ]
        );

        $this->assertProductLocalizedAttributes(
            $productConcrete2,
            [
                self::LOCALE_DE => [
                    self::ATTRIBUTE_KEY_LANG => 'Russisch',
                ],
                self::LOCALE_ENG => [
                    self::ATTRIBUTE_KEY_LANG => 'Russian',
                ],
            ]
        );

        $this->assertProductLocalizedAttributes(
            $productConcrete3,
            [
                self::LOCALE_DE => [],
                self::LOCALE_ENG => [],
            ]
        );

        $this->assertProductLocalizedAttributes(
            $productConcrete4,
            [
                self::LOCALE_DE => [],
                self::LOCALE_ENG => [],
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param array $expectedLocalizedAttributes
     *
     * @return void
     */
    private function assertProductLocalizedAttributes(
        ProductConcreteTransfer $productConcreteTransfer,
        array $expectedLocalizedAttributes
    ): void {
        foreach ($expectedLocalizedAttributes as $localeName => $attributes) {
            $spyProductLocalizedAttributes = $this->getProductLocalizedAttributesByIdProductAndLocale(
                $productConcreteTransfer->getIdProductConcrete(),
                $localeName
            );
            $localizedAttributes = json_decode($spyProductLocalizedAttributes->getAttributes(), true);
            self::assertEquals($attributes, $localizedAttributes);
        }
    }

    /**
     * @param int $idProduct
     * @param string $localeName
     *
     * @return \Propel\Runtime\Collection\Collection|\Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes[]
     */
    private function getProductLocalizedAttributesByIdProductAndLocale(int $idProduct, string $localeName): SpyProductLocalizedAttributes
    {
        return SpyProductLocalizedAttributesQuery::create()
            ->filterByFkProduct($idProduct)
            ->useLocaleQuery()
            ->filterByLocaleName($localeName)
            ->endUse()
            ->findOne();
    }

    /**
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\LocaleTransfer
     */
    private function setupLocale(string $localeName): LocaleTransfer
    {
        return $this->tester->haveLocale([
            LocaleTransfer::LOCALE_NAME => $localeName,
        ]);
    }

    /**
     * @param array $attributes
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    private function setupProduct(array $attributes): ProductConcreteTransfer
    {
        return $this->tester->haveProduct([
            ProductConcreteTransfer::LOCALIZED_ATTRIBUTES => array_map(
                function (LocaleTransfer $localeTransfer): array {
                    return $this->buildLocalizedAttributesTransfer($localeTransfer)->toArray();
                },
                $this->getLocaleFacade()->getLocaleCollection()
            ),
            ProductConcreteTransfer::ATTRIBUTES => $attributes,
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
     * @param string $attributeKey
     * @param string[][] $values
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer
     */
    private function createProductManagementAttributeTransfer(
        string $attributeKey,
        array $values
    ): ProductManagementAttributeTransfer {
        $productManagementAttributeTransfer = new ProductManagementAttributeTransfer();
        $productManagementAttributeTransfer->setKey($attributeKey);
        foreach ($values as $value => $localizedValues) {
            $attributeValueTransfer = new ProductManagementAttributeValueTransfer();
            $attributeValueTransfer->setValue($value);

            foreach ($localizedValues as $localeId => $localizedValue) {
                $attributeValueTranslationTransfer = new ProductManagementAttributeValueTranslationTransfer();
                $attributeValueTranslationTransfer->setFkLocale($localeId);
                $attributeValueTranslationTransfer->setTranslation($localizedValue);

                $attributeValueTransfer->addLocalizedValue($attributeValueTranslationTransfer);
            }

            $productManagementAttributeTransfer->addValue($attributeValueTransfer);
        }

        return $productManagementAttributeTransfer;
    }

    /**
     * @return \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    private function getLocaleFacade(): LocaleFacadeInterface
    {
        return $this->tester->getLocator()->locale()->facade();
    }

    /**
     * @return \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockProductAttributeFacade(): ProductAttributeFacadeInterface
    {
        return $this->createMock(ProductAttributeFacadeInterface::class);
    }

    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private function mockEventFacade(): EventFacadeInterface
    {
        return $this->createMock(EventFacade::class);
    }
}
