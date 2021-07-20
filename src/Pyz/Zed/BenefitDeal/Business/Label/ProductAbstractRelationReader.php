<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\BenefitDeal\Business\Label;

use Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer;
use Generated\Shared\Transfer\ProductLabelTransfer;
use Pyz\Zed\BenefitDeal\BenefitDealConfig;
use Pyz\Zed\BenefitDeal\Business\Exception\ProductLabelBenefitNotFoundException;
use Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface;
use Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface;

class ProductAbstractRelationReader implements ProductAbstractRelationReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface
     */
    protected $productLabelFacade;

    /**
     * @var \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface
     */
    protected $benefitDealRepository;

    /**
     * @var \Pyz\Zed\BenefitDeal\BenefitDealConfig
     */
    protected $benefitDealConfig;

    /**
     * @param \Spryker\Zed\ProductLabel\Business\ProductLabelFacadeInterface $productLabelFacade
     * @param \Pyz\Zed\BenefitDeal\Persistence\BenefitDealRepositoryInterface $benefitDealRepository
     * @param \Pyz\Zed\BenefitDeal\BenefitDealConfig $benefitDealConfig
     */
    public function __construct(
        ProductLabelFacadeInterface $productLabelFacade,
        BenefitDealRepositoryInterface $benefitDealRepository,
        BenefitDealConfig $benefitDealConfig
    ) {
        $this->productLabelFacade = $productLabelFacade;
        $this->benefitDealRepository = $benefitDealRepository;
        $this->benefitDealConfig = $benefitDealConfig;
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findBenefitProductLabelProductAbstractRelationChanges(): array
    {
        $labelEntity = $this->getBenefitLabel();
        if (!$labelEntity->getIsActive()) {
            return [];
        }

        $relationsTransfer = new ProductLabelProductAbstractRelationsTransfer();
        $relationsTransfer->setIdProductLabel(
            $labelEntity->getIdProductLabel()
        );

        $idsToAssign = $this->benefitDealRepository
            ->findProductAbstractIdsBecomingActiveByBenefitProductLabelId(
                $labelEntity->getIdProductLabel()
            );
        $relationsTransfer->setIdsProductAbstractToAssign($idsToAssign);

        $idsToDeAssign = $this->benefitDealRepository
            ->findProductAbstractIdsBecomingInactiveByBenefitProductLabelId(
                $labelEntity->getIdProductLabel()
            );
        $relationsTransfer->setIdsProductAbstractToDeAssign($idsToDeAssign);

        return [
            $relationsTransfer,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findShoppingPointProductLabelProductAbstractRelationChanges(): array
    {
        $labelEntity = $this->getShoppingPointLabel();
        if (!$labelEntity->getIsActive()) {
            return [];
        }

        $relationsTransfer = new ProductLabelProductAbstractRelationsTransfer();
        $relationsTransfer->setIdProductLabel(
            $labelEntity->getIdProductLabel()
        );

        $idsToAssign = $this->benefitDealRepository
            ->findProductAbstractIdsBecomingActiveByShoppingPointProductLabelId(
                $labelEntity->getIdProductLabel()
            );
        $relationsTransfer->setIdsProductAbstractToAssign($idsToAssign);

        $idsToDeAssign = $this->benefitDealRepository
            ->findProductAbstractIdsBecomingInactiveByShoppingPointProductLabelId(
                $labelEntity->getIdProductLabel()
            );
        $relationsTransfer->setIdsProductAbstractToDeAssign($idsToDeAssign);

        return [
            $relationsTransfer,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findInsteadOfProductLabelProductAbstractRelationChanges(): array
    {
        $labelEntity = $this->getInsteadOfLabel();
        if (!$labelEntity->getIsActive()) {
            return [];
        }

        $relationsTransfer = new ProductLabelProductAbstractRelationsTransfer();
        $relationsTransfer->setIdProductLabel(
            $labelEntity->getIdProductLabel()
        );

        $idsToAssign = $this->benefitDealRepository
            ->findProductAbstractIdsBecomingActiveByInsteadOfProductLabelId(
                $labelEntity->getIdProductLabel()
            );
        $relationsTransfer->setIdsProductAbstractToAssign($idsToAssign);

        $idsToDeAssign = $this->benefitDealRepository
            ->findProductAbstractIdsBecomingInactiveByInsteadOfProductLabelId(
                $labelEntity->getIdProductLabel()
            );
        $relationsTransfer->setIdsProductAbstractToDeAssign($idsToDeAssign);

        return [
            $relationsTransfer,
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelProductAbstractRelationsTransfer[]
     */
    public function findProductLabelProductAbstractRelationChanges(): array
    {
        return array_merge(
            $this->findBenefitProductLabelProductAbstractRelationChanges(),
            $this->findShoppingPointProductLabelProductAbstractRelationChanges(),
            $this->findInsteadOfProductLabelProductAbstractRelationChanges()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function getBenefitLabel(): ProductLabelTransfer
    {
        return $this->getProductLabelEntityByName(
            $this->benefitDealConfig->getLabelBenefitName()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function getShoppingPointLabel(): ProductLabelTransfer
    {
        return $this->getProductLabelEntityByName(
            $this->benefitDealConfig->getLabelShoppingPointName()
        );
    }

    /**
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function getInsteadOfLabel(): ProductLabelTransfer
    {
        return $this->getProductLabelEntityByName(
            $this->benefitDealConfig->getLabelInsteadOfName()
        );
    }

    /**
     * @param string $labelName
     *
     * @throws \Pyz\Zed\BenefitDeal\Business\Exception\ProductLabelBenefitNotFoundException
     *
     * @return \Generated\Shared\Transfer\ProductLabelTransfer
     */
    protected function getProductLabelEntityByName(string $labelName): ProductLabelTransfer
    {
        $productLabelTransfer = $this->productLabelFacade
            ->findLabelByLabelName($labelName);

        if ($productLabelTransfer === null) {
            throw new ProductLabelBenefitNotFoundException(sprintf(
                'Product Label "%1$s" doesn\'t exists. You can fix this problem by persisting a new Product Label entity into your database with "%1$s" name.',
                $labelName
            ));
        }

        return $productLabelTransfer;
    }
}
