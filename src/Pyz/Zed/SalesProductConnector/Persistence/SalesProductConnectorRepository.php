<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
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
