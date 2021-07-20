<?php

declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Product\Communication\Plugin\Product\DataHealer;

use Generated\Shared\Transfer\EventEntityTransfer;
use Generator;
use Propel\Runtime\ActiveQuery\Criteria;
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
class SellableProductAttributeHealerPlugin extends AbstractPlugin implements ProductDataHealerPluginInterface
{
    private const QUERY_BULK_SIZE = 500;

    /**
     * @var \Psr\Log\LoggerInterface|\Symfony\Component\Console\Logger\ConsoleLogger
     */
    private $logger;

    /**
     * @var int[]
     */
    private $updatedConcreteProductIds = [];

    /**
     * @var int[]
     */
    private $updatedAbstractProductIds = [];

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'SELLABLE_PRODUCT_ATTRIBUTE_HEALER';
    }

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     *
     * @return void
     */
    public function execute(?LoggerInterface $logger = null): void
    {
        $this->logger = $logger;

        /** @var \Orm\Zed\Product\Persistence\SpyProduct[] $spyProductBulk */
        foreach ($this->getConcreteProducts() as $spyProductBulk) {
            foreach ($spyProductBulk as $spyProduct) {
                $this->updatedConcreteProductIds[] = $spyProduct->getIdProduct();
                $spyProduct->setAttributes(
                    $this->fixAttributes(
                        $spyProduct->getAttributes()
                    )
                );
                $spyProduct->save();

                $this->logger->info(
                    sprintf(
                        'Sellable attributes are converted to boolean for the Product Concrete (ID: %d) .',
                        $spyProduct->getIdProduct()
                    )
                );
            }
        }

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstract[] $spyProductBulk */
        foreach ($this->getAbstractProducts() as $spyProductBulk) {
            foreach ($spyProductBulk as $spyProduct) {
                $this->updatedAbstractProductIds[] = $spyProduct->getIdProductAbstract();
                $spyProduct->setAttributes(
                    $this->fixAttributes(
                        $spyProduct->getAttributes()
                    )
                );
                $spyProduct->save();

                $this->logger->info(
                    sprintf(
                        'Sellable attributes are converted to boolean for the Product Abstract (ID: %d) .',
                        $spyProduct->getIdProductAbstract()
                    )
                );
            }
        }

        /** @var \Orm\Zed\Product\Persistence\SpyProductAbstractLocalizedAttributes[] $spyProductBulk */
        foreach ($this->getLocalizedAbstractProducts() as $spyProductBulk) {
            foreach ($spyProductBulk as $spyProduct) {
                $this->updatedAbstractProductIds[] = $spyProduct->getFkProductAbstract();
                $spyProduct->setAttributes(
                    $this->fixAttributes(
                        $spyProduct->getAttributes()
                    )
                );
                $spyProduct->save();
            }
        }

        /** @var \Orm\Zed\Product\Persistence\SpyProductLocalizedAttributes[] $spyProductBulk */
        foreach ($this->getLocalizedProducts() as $spyProductBulk) {
            foreach ($spyProductBulk as $spyProduct) {
                $this->updatedConcreteProductIds[] = $spyProduct->getFkProduct();
                $spyProduct->setAttributes(
                    $this->fixAttributes(
                        $spyProduct->getAttributes()
                    )
                );
                $spyProduct->save();
            }
        }

        $this->publishAffectedProducts();
    }

    /**
     * @param string $attributesJson
     *
     * @return string
     */
    private function fixAttributes(string $attributesJson): string
    {
        $attributes = $this->decodeAttributes($attributesJson);
        foreach ($attributes as $key => $value) {
            if (substr($key, 0, 9) === 'sellable_') {
                $attributes[$key] = (bool)$value;
            }
        }

        return $this->encodeAttributes($attributes);
    }

    /**
     * @return void
     */
    private function publishAffectedProducts(): void
    {
        $this->triggerPublishForProducts(
            $this->updatedConcreteProductIds,
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
    private function getConcreteProducts(): Generator
    {
        $criteria = $this
            ->getQueryContainer()
            ->queryProduct()
            ->filterByAttributes("%\"sellable_de\":\"0\"%", Criteria::LIKE)
            ->addOr("spy_product.attributes", "%\"sellable_de\":\"1\"%", Criteria::LIKE)
            ->orderByIdProduct();

        return $this->paginateCriteria($criteria);
    }

    /**
     * @return \Generator
     */
    private function getAbstractProducts(): Generator
    {
        $criteria = $this
            ->getQueryContainer()
            ->queryProductAbstract()
            ->filterByAttributes("%\"sellable_de\":\"0\"%", Criteria::LIKE)
            ->addOr("spy_product_abstract.attributes", "%\"sellable_de\":\"1\"%", Criteria::LIKE)
            ->orderByIdProductAbstract();

        return $this->paginateCriteria($criteria);
    }

    /**
     * @return \Generator
     */
    private function getLocalizedAbstractProducts(): Generator
    {
        $criteria = $this
            ->getQueryContainer()
            ->queryProductAbstractLocalizedAttributes(1)
            ->clear()
            ->filterByAttributes("%\"sellable_de\":\"0\"%", Criteria::LIKE)
            ->addOr("spy_product_abstract_localized_attributes.attributes", "%\"sellable_de\":\"1\"%", Criteria::LIKE)
            ->orderByIdAbstractAttributes();

        return $this->paginateCriteria($criteria);
    }

    /**
     * @return \Generator
     */
    private function getLocalizedProducts(): Generator
    {
        $criteria = $this
            ->getQueryContainer()
            ->queryProductLocalizedAttributes(1)
            ->clear()
            ->filterByAttributes("%\"sellable_de\":\"0\"%", Criteria::LIKE)
            ->addOr("spy_product_localized_attributes.attributes", "%\"sellable_de\":\"1\"%", Criteria::LIKE)
            ->orderByIdProductAttributes();

        return $this->paginateCriteria($criteria);
    }

    /**
     * @param \Propel\Runtime\ActiveQuery\Criteria $criteria
     *
     * @return \Generator
     */
    private function paginateCriteria(Criteria $criteria): Generator
    {
        $totalProductsCount = $criteria->count();

        for ($offset = 0; $offset < $totalProductsCount; $offset += self::QUERY_BULK_SIZE) {
            yield $criteria
                ->offset($offset)
                ->limit(self::QUERY_BULK_SIZE)
                ->find();
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
     * @return \Spryker\Zed\Event\Business\EventFacadeInterface
     */
    private function getEventFacade(): EventFacadeInterface
    {
        return $this->getFactory()->getEventFacade();
    }
}
