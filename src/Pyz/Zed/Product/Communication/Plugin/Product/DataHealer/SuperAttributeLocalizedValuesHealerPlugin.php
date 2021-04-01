<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Communication\Plugin\Product\DataHealer;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generator;
use Orm\Zed\Product\Persistence\SpyProduct;
use Psr\Log\LoggerInterface;
use Pyz\Zed\Product\Dependency\Plugin\ProductDataHealerPluginInterface;
use Spryker\Zed\Event\Business\EventFacadeInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\Product\Dependency\ProductEvents;

/**
 * @method \Pyz\Zed\Product\Communication\ProductCommunicationFactory getFactory()
 * @method \Pyz\Zed\Product\Business\ProductFacadeInterface getFacade()
 * @method \Pyz\Zed\Product\Persistence\ProductQueryContainerInterface getQueryContainer()
 */
class SuperAttributeLocalizedValuesHealerPlugin extends AbstractPlugin implements ProductDataHealerPluginInterface
{
    private const RESOURCE_NAME = 'SUPER_ATTRIBUTES_LOCALIZED_VALUES';
    private const QUERY_BULK_SIZE = 500;

    /**
     * @var \Psr\Log\LoggerInterface|\Symfony\Component\Console\Logger\ConsoleLogger
     */
    private $logger;

    /**
     * @var array
     */
    private $superAttributesValueMap = [];

    /**
     * @var int[]
     */
    private $updatedProductIds = [];

    /**
     * @var int[]
     */
    private $updatedAbstractProductIds = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::RESOURCE_NAME;
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function execute(?LoggerInterface $logger = null): void
    {
        $this->logger = $logger;
        $this->loadMappedSuperAttributeLocalizedValues();
        $superAttributeKeys = array_keys($this->superAttributesValueMap);

        foreach ($this->getProducts() as $spyProductBulk) {
            foreach ($spyProductBulk as $spyProduct) {
                $attributes = $this->decodeAttributes($spyProduct->getAttributes());
                $relevantSuperAttributes = array_intersect(array_keys($attributes), $superAttributeKeys);
                $this->addMissingProductLocalizedSuperAttributeValues($spyProduct, $attributes, $relevantSuperAttributes);
            }
        }

        $this->publishAffectedProducts();
    }

    /**
     * @return void
     */
    private function publishAffectedProducts(): void
    {
        $this->triggerPublishForProducts(
            $this->updatedProductIds,
            ProductEvents::PRODUCT_CONCRETE_PUBLISH
        );

        $this->triggerPublishForProducts(
            $this->updatedAbstractProductIds,
            ProductEvents::PRODUCT_ABSTRACT_PUBLISH
        );
    }

    /**
     * @param int[] $ids
     * @param string $eventName
     *
     * @return void
     */
    private function triggerPublishForProducts(array $ids, string $eventName): void
    {
        $eventEntityTransfers = $this->mapIdsToEventEntityTransfers(array_unique($ids), $eventName);
        $this->getEventFacade()->triggerBulk($eventName, $eventEntityTransfers);
    }

    /**
     * @param int[] $ids
     * @param string $eventName
     *
     * @return \Generated\Shared\Transfer\EventEntityTransfer[]
     */
    private function mapIdsToEventEntityTransfers(array $ids, string $eventName): array
    {
        return array_map(
            static function (int $id) use ($eventName): EventEntityTransfer {
                return (new EventEntityTransfer())->setId($id)->setEvent($eventName);
            },
            $ids
        );
    }

    /**
     * @return \Generator
     */
    private function getProducts(): Generator
    {
        $totalProductsCount = $this
            ->getQueryContainer()
            ->queryProduct()
            ->count();

        for ($offset = 0; $offset < $totalProductsCount; $offset += self::QUERY_BULK_SIZE) {
            yield $this
                ->getQueryContainer()
                ->queryProduct()
                ->orderByIdProduct()
                ->offset($offset)
                ->limit(self::QUERY_BULK_SIZE)
                ->find();
        }
    }

    /**
     * @param string $key
     * @param string $value
     * @param int $idLocale
     *
     * @return string|null
     */
    private function getSuperAttributeValueTranslation(string $key, string $value, int $idLocale): ?string
    {
        return $this->superAttributesValueMap[$key][$value][$idLocale] ?? null;
    }

    /**
     * @return void
     */
    private function loadMappedSuperAttributeLocalizedValues(): void
    {
        $productManagementSuperAttributes = $this->getProductManagementSuperAttributes();
        foreach ($productManagementSuperAttributes as $productManagementAttributeTransfer) {
            $key = $productManagementAttributeTransfer->getKey();

            foreach ($productManagementAttributeTransfer->getValues() as $attributeValueTransfer) {
                $value = $attributeValueTransfer->getValue();

                foreach ($attributeValueTransfer->getLocalizedValues() as $attributeValueTranslationTransfer) {
                    $idLocale = $attributeValueTranslationTransfer->getFkLocale();
                    $this->superAttributesValueMap[$key][$value][$idLocale] = $attributeValueTranslationTransfer->getTranslation();
                }
            }
        }
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProduct $spyProduct
     * @param array $attributes
     * @param array $relevantSuperAttributes
     *
     * @return void
     */
    private function addMissingProductLocalizedSuperAttributeValues(
        SpyProduct $spyProduct,
        array $attributes,
        array $relevantSuperAttributes
    ): void {
        foreach ($spyProduct->getSpyProductLocalizedAttributessJoinLocale() as $spyProductLocalizedAttributes) {
            $locale = $spyProductLocalizedAttributes->getLocale();
            $productLocalizedAttributes = $this->decodeAttributes($spyProductLocalizedAttributes->getAttributes());
            $translatedSuperAttributes = array_intersect(array_keys($productLocalizedAttributes), $relevantSuperAttributes);
            if (count($translatedSuperAttributes) === count($relevantSuperAttributes)) {
                continue;
            }

            $nonTranslatedAttributes = array_diff($relevantSuperAttributes, $translatedSuperAttributes);

            foreach ($nonTranslatedAttributes as $nonTranslatedAttribute) {
                $attributeValue = $attributes[$nonTranslatedAttribute];

                $supposedValueTranslation = $this->getSuperAttributeValueTranslation(
                    $nonTranslatedAttribute,
                    $attributeValue,
                    $locale->getIdLocale()
                );
                if ($supposedValueTranslation === null) {
                    continue;
                }

                $productLocalizedAttributes[$nonTranslatedAttribute] = $supposedValueTranslation;
                $spyProductLocalizedAttributes->setAttributes($this->encodeAttributes($productLocalizedAttributes));
                $spyProductLocalizedAttributes->save();

                if ($this->logger) {
                    $this->logger->info(sprintf(
                        'Product (ID %d) attribute %s value for locale %s translated to %s.',
                        $spyProduct->getIdProduct(),
                        $nonTranslatedAttribute,
                        $locale->getLocaleName(),
                        $supposedValueTranslation
                    ));
                }

                $this->updatedProductIds[] = $spyProduct->getIdProduct();
                $this->updatedAbstractProductIds[] = $spyProduct->getFkProductAbstract();
            }
        }
    }

    /**
     * @param array $attributes
     *
     * @return string
     */
    private function encodeAttributes(array $attributes): string
    {
        return $this->getFactory()->getUtilEncodingService()->encodeJson($attributes);
    }

    /**
     * @param string|null $attributesString
     *
     * @return array
     */
    private function decodeAttributes(?string $attributesString): array
    {
        return $this->getFactory()->getUtilEncodingService()->decodeJson($attributesString, true);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductManagementAttributeTransfer[]
     */
    private function getProductManagementSuperAttributes(): array
    {
        return $this->getFactory()->getProductAttributeFacade()->getProductSuperAttributeCollection();
    }

    /**
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    private function getEventFacade(): EventFacadeInterface
    {
        return $this->getFactory()->getEventFacade();
    }
}
