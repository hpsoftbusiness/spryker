<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

/**
 * @method \Pyz\Zed\ProductAttributeStorage\ProductAttributeStorageConfig getConfig()
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageEntityManagerInterface getEntityManager()
 * @method \Pyz\Zed\ProductAttributeStorage\Business\ProductAttributeStorageBusinessFactory getFacade()
 * @method \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageRepositoryInterface getRepository()
 */
class ProductAttributeStorageCommunicationFactory extends AbstractCommunicationFactory
{
}
