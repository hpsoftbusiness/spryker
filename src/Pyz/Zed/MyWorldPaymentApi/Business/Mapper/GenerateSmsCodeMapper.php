<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Mapper;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;

class GenerateSmsCodeMapper implements MyWorldPaymentApiMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return array
     */
    public function buildRequestArray(MyWorldApiRequestTransfer $requestTransfer): array
    {
        return [];
    }
}
