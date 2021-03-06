<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Mapper;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;

class ValidateSmsCodeMapper extends AbstractMapper implements MyWorldPaymentApiMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return void
     */
    protected function validateRequestTransfer(MyWorldApiRequestTransfer $requestTransfer): void
    {
        $requestTransfer->requirePaymentCodeValidateRequest();

        $requestTransfer->getPaymentCodeValidateRequest()
            ->requireConfirmationCode()
            ->requireSessionId();
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return array
     */
    public function buildRequestArray(MyWorldApiRequestTransfer $requestTransfer): array
    {
        $this->validateRequestTransfer($requestTransfer);

        $requestArray = $requestTransfer->getPaymentCodeValidateRequest()->toArray(true, true);

        $requestArray = $this->removeRedundantParams($requestArray);
        $requestArray = $this->capitalizeArrayKeysRecursive($requestArray);
        $requestArray = $this->normalizeArrayKeys($requestArray);

        return $requestArray;
    }
}
