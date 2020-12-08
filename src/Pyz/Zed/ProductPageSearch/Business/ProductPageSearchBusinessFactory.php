<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Zed\ProductPageSearch\Business;

use Pyz\Zed\ProductPageSearch\Business\DataMapper\ProductAbstractSearchDataMapper;
use Pyz\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapper;
use Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper;
use Spryker\Zed\ProductPageSearch\Business\ProductPageSearchBusinessFactory as SprykerProductPageSearchBusinessFactory;

class ProductPageSearchBusinessFactory extends SprykerProductPageSearchBusinessFactory
{
    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\Mapper\ProductPageSearchMapperInterface
     */
    protected function createProductPageMapper()
    {
        return new ProductPageSearchMapper(
            $this->createProductPageAttribute(),
            $this->createProductAbstractSearchDataMapper(),
            $this->getUtilEncoding()
        );
    }

    /**
     * @return \Spryker\Zed\ProductPageSearch\Business\DataMapper\AbstractProductSearchDataMapper
     */
    public function createProductAbstractSearchDataMapper(): AbstractProductSearchDataMapper
    {
        return new ProductAbstractSearchDataMapper(
            $this->createPageMapBuilder(),
            $this->getSearchFacade(),
            $this->getProductSearchFacade(),
            $this->getProductAbstractMapExpanderPlugins()
        );
    }
}
