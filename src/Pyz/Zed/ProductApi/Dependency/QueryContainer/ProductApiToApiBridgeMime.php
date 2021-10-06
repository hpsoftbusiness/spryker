<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiItemTransfer;
use Spryker\Zed\Api\Persistence\ApiQueryContainerInterface;

/**
 * @deprecated Please use Glue API instead (Pyz/Glue/ProductFeedRestApi)
 */
class ProductApiToApiBridgeMime implements ProductApiToApiInterface
{
    /**
     * @var \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface $apiQueryContainer
     */
    public function __construct(ApiQueryContainerInterface $apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param int|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem($data, $id = null): ApiItemTransfer
    {
        return $this->apiQueryContainer->createApiItem($data, $id);
    }
}
