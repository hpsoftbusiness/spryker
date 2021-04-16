<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Api\Communication\Plugin;

use Generated\Shared\Transfer\ApiRequestTransfer;
use Generated\Shared\Transfer\ApiResponseTransfer;
use Spryker\Zed\Api\Communication\Plugin\ApiControllerListenerPlugin as SprykerApiControllerListenerPlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Pyz\Zed\Api\Communication\ApiCommunicationFactory getFactory()
 */
class ApiControllerListenerPlugin extends SprykerApiControllerListenerPlugin
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $requestTransfer
     * @param \Generated\Shared\Transfer\ApiResponseTransfer $responseTransfer
     * @param \Symfony\Component\HttpFoundation\Response $responseObject
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function transformToResponse(
        ApiRequestTransfer $requestTransfer,
        ApiResponseTransfer $responseTransfer,
        Response $responseObject
    ) {
        return $this->getFactory()
            ->createTransformerByType($requestTransfer, $responseTransfer)
            ->transform($requestTransfer, $responseTransfer, $responseObject);
    }
}
