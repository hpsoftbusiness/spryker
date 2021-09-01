<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAttributeStorage\Business\ProductManagementAttributeVisibilityPublisher;

use Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface;
use Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageEntityManagerInterface;

class ProductManagementAttributeVisibilityPublisher implements ProductManagementAttributeVisibilityPublisherInterface
{
    /**
     * @var \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @var \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageEntityManagerInterface
     */
    protected $productAttributeStorageEntityManager;

    /**
     * @param \Pyz\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     * @param \Pyz\Zed\ProductAttributeStorage\Persistence\ProductAttributeStorageEntityManagerInterface $productAttributeStorageEntityManager
     */
    public function __construct(
        ProductAttributeFacadeInterface $productAttributeFacade,
        ProductAttributeStorageEntityManagerInterface $productAttributeStorageEntityManager
    ) {
        $this->productAttributeFacade = $productAttributeFacade;
        $this->productAttributeStorageEntityManager = $productAttributeStorageEntityManager;
    }

    /**
     * @return void
     */
    public function publish(): void
    {
        $this->productAttributeStorageEntityManager->storeAttributeVisibilityData(
            $this->productAttributeFacade->getKeysToShowOnPdp()
        );
    }
}
