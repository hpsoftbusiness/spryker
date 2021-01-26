<?php

namespace Pyz\Zed\ProductDataImport\Business\Model;

use Generated\Shared\Transfer\ProductDataImportTransfer;
use Orm\Zed\ProductDataImport\Persistence\SpyProductDataImport;
use Pyz\Zed\ProductDataImport\Persistence\ProductDataImportQueryContainerInterface;
use Spryker\Zed\Kernel\Persistence\EntityManager\TransactionTrait;

class ProductDataImport implements ProductDataImportInterface
{
    use TransactionTrait;

    /**
     * @var ProductDataImportQueryContainerInterface
     */
    private $queryContainer;

    /**
     * ProductDataImport constructor.
     * @param ProductDataImportQueryContainerInterface $queryContainer
     */
    public function __construct(ProductDataImportQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param ProductDataImportTransfer $transfer
     * @return ProductDataImportTransfer
     */
    public function add(ProductDataImportTransfer $transfer): ProductDataImportTransfer
    {
        return $this->getTransactionHandler()->handleTransaction(
            function () use ($transfer) {
                return $this->executeAddTransaction($transfer);
            }
        );
    }

    /**
     * @param ProductDataImportTransfer $productDataImportTransfer
     *
     * @return ProductDataImportTransfer
     *
     * @throws \Propel\Runtime\Exception\PropelException
     */
    protected function executeAddTransaction(ProductDataImportTransfer $productDataImportTransfer): ProductDataImportTransfer
    {
        $productDataImportEntity = new SpyProductDataImport();
        $productDataImportEntity->fromArray($productDataImportTransfer->toArray());

        $productDataImportEntity->save();

        return $productDataImportTransfer;
    }
}
