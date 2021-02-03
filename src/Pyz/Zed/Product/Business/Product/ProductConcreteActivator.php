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

class ProductConcreteActivator extends SprykerProductConcreteActivator
{
    /**
     * @var \Spryker\Zed\Product\Business\Attribute\AttributeEncoderInterface
     */
    protected $attributeEncoder;

    public function __construct(
        ProductAbstractStatusCheckerInterface $productAbstractStatusChecker,
        ProductAbstractManagerInterface $productAbstractManager,
        ProductConcreteManagerInterface $productConcreteManager,
        ProductUrlManagerInterface $productUrlManager,
        ProductConcreteTouchInterface $productConcreteTouch,
        ProductQueryContainerInterface $productQueryContainer,
        AttributeEncoderInterface $attributeEncoder
    ) {
        parent::__construct(
            $productAbstractStatusChecker,
            $productAbstractManager,
            $productConcreteManager,
            $productUrlManager,
            $productConcreteTouch,
            $productQueryContainer
        );
        $this->attributeEncoder = $attributeEncoder;
    }

    /**
     * @param int $idProductAbstract
     */
    public function markAbstractProductAsRemoved(int $idProductAbstract)
    {
        $this->getTransactionHandler()->handleTransaction(
            function () use ($idProductAbstract) {
                $this->executeMarkAbstractProductAsRemovedTransaction($idProductAbstract);
            }
        );
    }

    /**
     * @param int $idProductAbstract
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
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

            $this->executeDeactivateProductConcreteTransaction($productConcreteTransfer->getIdProductConcrete());
        }
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductAbstractTransfer|null
     *
     * @throws \Spryker\Zed\Product\Business\Exception\ProductConcreteNotFoundException
     */
    protected function getProductAbstractTransferById(int $idProductAbstract
    ): ?\Generated\Shared\Transfer\ProductAbstractTransfer {
        $productAbstractTransfer = $this->productAbstractManager->findProductAbstractById($idProductAbstract);
        $this->assertProductAbstract($idProductAbstract, $productAbstractTransfer);

        return $productAbstractTransfer;
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
