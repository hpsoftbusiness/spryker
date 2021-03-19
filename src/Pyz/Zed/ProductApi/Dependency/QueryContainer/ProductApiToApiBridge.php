<?php

namespace Pyz\Zed\ProductApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiItemTransfer;
use Spryker\Zed\Api\Persistence\ApiQueryContainerInterface;

class ProductApiToApiBridge implements ProductApiToApiInterface
{
    /**
     * @var ApiQueryContainerInterface
     */
    protected $apiQueryContainer;

    /**
     * @param ApiQueryContainerInterface $apiQueryContainer
     */
    public function __construct(ApiQueryContainerInterface $apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param int|null $id
     *
     * @return ApiItemTransfer
     */
    public function createApiItem($data, $id = null): ApiItemTransfer
    {
        return $this->apiQueryContainer->createApiItem($data, $id);
    }
}
