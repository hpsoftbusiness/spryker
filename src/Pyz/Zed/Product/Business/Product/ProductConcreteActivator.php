<?php

namespace Pyz\Zed\Product\Business\Product;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface;
use Spryker\Zed\Product\Business\Product\ProductAbstractManagerInterface;
use Spryker\Zed\Product\Business\Product\ProductConcreteActivator as SprykerProductConcreteActivator;
use Spryker\Zed\Product\Business\Product\ProductConcreteManagerInterface;
use Spryker\Zed\Product\Business\Product\Status\ProductAbstractStatusCheckerInterface;
use Spryker\Zed\Product\Business\Product\Touch\ProductConcreteTouchInterface;
use Spryker\Zed\Product\Business\Product\Url\ProductUrlManagerInterface;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductConcreteActivator
{
    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    protected $attributeEncoder;

    /** @var SprykerProductConcreteActivator */
    protected $sprykerProductConcreteActivator;
    /**
     * @var ProductAbstractStatusCheckerInterface
     */
    private $productAbstractStatusChecker;
    /**
     * @var ProductAbstractManagerInterface
     */
    private $productAbstractManager;
    /**
     * @var ProductConcreteManagerInterface
     */
    private $productConcreteManager;
    /**
     * @var ProductUrlManagerInterface
     */
    private $productUrlManager;
    /**
     * @var ProductConcreteTouchInterface
     */
    private $productConcreteTouch;
    /**
     * @var ProductQueryContainerInterface
     */
    private $productQueryContainer;

    public function __construct(
        ProductAbstractStatusCheckerInterface $productAbstractStatusChecker,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductUrlManagerInterface $productUrlManager,
        ProductConcreteTouchInterface $productConcreteTouch,
        ProductQueryContainerInterface $productQueryContainer,
        AttributeEncoderInterface $attributeEncoder
    ) {

        $this->sprykerProductConcreteActivator = new SprykerProductConcreteActivator(
            $productAbstractStatusChecker,
            $productAbstractManager,
            $productConcreteManager,
            $productUrlManager,
            $productConcreteTouch,
            $productQueryContainer
        );
        $this->attributeEncoder = $attributeEncoder;
        $this->productAbstractStatusChecker = $productAbstractStatusChecker;
        $this->productAbstractManager = $productAbstractManager;
        $this->productConcreteManager = $productConcreteManager;
        $this->productUrlManager = $productUrlManager;
        $this->productConcreteTouch = $productConcreteTouch;
        $this->productQueryContainer = $productQueryContainer;
    }

    /**
     * @param int $idProductAbstract
     */
    public function markAbstractProductAsRemoved(int $idProductAbstract)
    {
        $this->sprykerProductConcreteActivator->getTransactionHandler()->handleTransaction(
            function () use ($idProductAbstract) {
                $this->executeMarkAbstractProductAsRemovedTransaction($idProductAbstract);
            }
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     * @throws \Exception
     */
    protected function executeMarkAbstractProductAsRemovedTransaction(int $idProductAbstract): void
    {
        $productAbstractTransfer = $this->getProductAbstractTransferById($idProductAbstract);
        $productAbstractTransfer->setIsRemoved(true);
        $productAbstractTransfer->setSku($this->generateRemovedSkuValue($productAbstractTransfer->getSku()));
        $this->productAbstractManager->saveProductAbstract($productAbstractTransfer);

        $productConcreteTransfers = $this->getProductConcreteTransfers($idProductAbstract);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteTransfer->setIsRemoved(true);
            $productConcreteTransfer->setSku($this->generateRemovedSkuValue($productConcreteTransfer->getSku()));
            $this->persistProductConcreteForSoftRemoved($productConcreteTransfer);

            $this->sprykerProductConcreteActivator->deactivateProductConcrete(
                $productConcreteTransfer->getIdProductConcrete()
            );
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     *
     */
    protected function getProductAbstractTransferById(int $idProductAbstract
    ): ?\Generated\Shared\Transfer\ProductAbstractTransfer {
        return $this->productAbstractManager->findProductAbstractById($idProductAbstract);
    }

    /**
     * @param int $idProductAbstract
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProductConcreteTransfers(int $idProductAbstract): array
    {
        return $this->productConcreteManager->getConcreteProductsByAbstractProductId($idProductAbstract);
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function generateRemovedSkuValue(string $sku): string
    {
        return sprintf('%s-removed-%s', $sku, (string)time());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Orm\Zed\Product\Persistence\SpyProduct
     *
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Spryker\Zed\Propel\Business\Exception\AmbiguousComparisonException
     */
    private function persistProductConcreteForSoftRemoved(ProductConcreteTransfer $productConcreteTransfer
    ): \Orm\Zed\Product\Persistence\SpyProduct {
        $productConcreteTransfer
            ->requireSku()
            ->requireFkProductAbstract();

        $encodedAttributes = $this->attributeEncoder->encodeAttributes(
            $productConcreteTransfer->getAttributes()
        );

        $productConcreteEntity = $this->productQueryContainer
            ->queryProduct()
            ->filterByIdProduct($productConcreteTransfer->getIdProductConcrete())
            ->findOneOrCreate();

        $productConcreteData = $productConcreteTransfer->modifiedToArray();

        if (isset($productConcreteData[ProductConcreteTransfer::ATTRIBUTES])) {
            unset($productConcreteData[ProductConcreteTransfer::ATTRIBUTES]);
        }

        $productConcreteEntity->fromArray($productConcreteData);
        $productConcreteEntity->setAttributes($encodedAttributes);

        $productConcreteEntity->save();

        return $productConcreteEntity;
    }
}
