<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Mapper;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;

class CreateRefundMapper extends AbstractMapper implements MyWorldPaymentApiMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return void
     */
    protected function validateRequestTransfer(MyWorldApiRequestTransfer $requestTransfer): void
    {
        $requestTransfer->requirePaymentRefundRequest();

        $requestTransfer->getPaymentRefundRequest()
            ->requirePaymentId()
            ->requireAmount()
            ->requireCurrency()
            ->requirePartialRefunds();
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return array
     */
    public function buildRequestArray(MyWorldApiRequestTransfer $requestTransfer): array
    {
        $this->validateRequestTransfer($requestTransfer);

        $requestArray = $requestTransfer->getPaymentRefundRequest()->toArray(true, true);

        $requestArray = $this->capitalizeArrayKeysRecursive($requestArray);
        $requestArray = $this->normalizeArrayKeys($requestArray);

        return $requestArray;
    }
}
