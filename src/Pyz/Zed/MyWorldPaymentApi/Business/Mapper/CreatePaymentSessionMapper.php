<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPaymentApi\Business\Mapper;

use ArrayObject;
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
     * Override:
     * - Shopping points redeeming request needs to have 0 total Amount and original method filters it out.
     *
     * @param array $data
     *
     * @return array
     */
    protected function removeRedundantParams(array $data): array
    {
        $data = array_filter($data, function ($item) {
            if ($item instanceof ArrayObject) {
                return $item->count() !== 0;
            }

            if (is_array($item)) {
                return !empty($item);
            }

            return $item !== null;
        });

        foreach ($data as $key => $value) {
            if (is_array($value) || $value instanceof ArrayObject) {
                $data[$key] = $this->removeRedundantParams($value);
            }
        }

        return $data;
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
