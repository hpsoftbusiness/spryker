<?php

namespace Pyz\Zed\ProductApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiItemTransfer;

interface ProductApiToApiInterface
{
    /**
     * @param array|\Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param int|null $id
     *
     * @return ApiItemTransfer
     */
    public function createApiItem($data, $id = null): ApiItemTransfer;
}
