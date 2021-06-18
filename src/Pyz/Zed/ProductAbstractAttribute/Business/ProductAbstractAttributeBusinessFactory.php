<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAbstractAttribute\Business;

use Pyz\Zed\Product\Business\ProductFacade;
use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Pyz\Zed\ProductAbstractAttribute\Business\Propel\ProductAbstractAttributePropelWriter;
use Pyz\Zed\ProductAbstractAttribute\Business\Propel\ProductAbstractAttributePropelWriterInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\ProductAbstractAttribute\Persistence\ProductAbstractAttributeRepositoryInterface getRepository()
 */
class ProductAbstractAttributeBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Pyz\Zed\ProductAbstractAttribute\Business\Propel\ProductAbstractAttributePropelWriterInterface
     */
    public function createProductAbstractAttributePropelWriter(): ProductAbstractAttributePropelWriterInterface
    {
        return new ProductAbstractAttributePropelWriter(
            $this->createProductFacade(),
            $this->getEntityManager(),
            $this->getRepository()
        );
    }

    /**
     * @return \Pyz\Zed\Product\Business\ProductFacadeInterface
     */
    public function createProductFacade(): ProductFacadeInterface
    {
        return new ProductFacade();
    }
}
