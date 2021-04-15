<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Mapper;

use Generated\Shared\Transfer\MyWorldApiRequestTransfer;
use Generated\Shared\Transfer\PaymentSessionRequestTransfer;

class CreatePaymentSessionMapper extends AbstractMapper implements MyWorldPaymentApiMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return array
     */
    public function buildRequestArray(MyWorldApiRequestTransfer $requestTransfer): array
    {
        $this->validateRequestTransfer($requestTransfer);

        $requestArray = $requestTransfer->getPaymentSessionRequest()->toArray(true, true);

        unset($requestArray[PaymentSessionRequestTransfer::SSO_ACCESS_TOKEN]);

        $requestArray = $this->removeRedundantParams($requestArray);
        $requestArray = $this->capitalizeArrayKeysRecursive($requestArray);
        $requestArray = $this->normalizeArrayKeys($requestArray);

        return $requestArray;
    }

    /**
     * @param \Generated\Shared\Transfer\MyWorldApiRequestTransfer $requestTransfer
     *
     * @return void
     */
    protected function validateRequestTransfer(MyWorldApiRequestTransfer $requestTransfer): void
    {
        $requestTransfer->requirePaymentSessionRequest();

        $requestTransfer->getPaymentSessionRequest()
            ->requireSsoAccessToken()
            ->requireAmount()
            ->requireCurrencyId()
            ->requireFlows()
            ->requirePaymentOptions()
            ->requireReference();

        $requestTransfer->getPaymentSessionRequest()
            ->getSsoAccessToken()
            ->requireAccessToken();

        $flowsTransfer = $requestTransfer->getPaymentSessionRequest()->getFlows();

        /** @var \Generated\Shared\Transfer\FlowsTransfer $flowTransfer */
        foreach ($flowsTransfer as $flowTransfer) {
            $flowTransfer->requireType();

            foreach ($flowTransfer->getMwsDirect() as $mwsDirectPaymentOptionTransfer) {
                $mwsDirectPaymentOptionTransfer->requireAmount()
                    ->requirePaymentOptionId();
            }
        }
    }
}
