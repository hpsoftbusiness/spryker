<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\SalesProductConnector\Persistence;

use Spryker\Zed\SalesProductConnector\Persistence\SalesProductConnectorRepository as SprykerSalesProductConnectorRepository;

/**
 * @method \Pyz\Zed\SalesProductConnector\Persistence\SalesProductConnectorPersistenceFactory getFactory()
 */
class SalesProductConnectorRepository extends SprykerSalesProductConnectorRepository
{
    /**
     * @param string[] $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    public function getRawProductConcreteTransfersByConcreteSkus(array $productConcreteSkus): array
    {
        $productQuery = $this->getFactory()
            ->getProductQueryContainer()
            ->queryProduct()
            ->joinWithSpyProductLocalizedAttributes()
                ->useSpyProductLocalizedAttributesQuery()
            ->joinWithLocale()
            ->endUse()
            ->filterBySku_In($productConcreteSkus);

        return $this->getFactory()
            ->createProductMapper()
            ->mapProductEntityCollectionToRawProductConcreteTransfers($productQuery->find());
    }
}
