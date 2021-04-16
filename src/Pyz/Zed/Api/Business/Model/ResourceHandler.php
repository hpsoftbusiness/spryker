<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Api\Business\Model;

use Pyz\Shared\Api\ApiConstants;
use Pyz\Zed\Api\Business\Auth\AuthInterface;
use Pyz\Zed\Api\Communication\Transformer\GetTransformerTypeInterface;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Business\Exception\ApiDispatchingException;
use Spryker\Zed\Api\Business\Model\ResourceHandler as SprykerResourceHandler;

class ResourceHandler extends SprykerResourceHandler
{
    /**
     * @param string $resource
     * @param string $method
     * @param int|null $id
     * @param array $params
     *
     * @throws \Spryker\Zed\Api\Business\Exception\ApiDispatchingException
     *
     * @return \Generated\Shared\Transfer\ApiOptionsTransfer|\Generated\Shared\Transfer\ApiItemTransfer|\Generated\Shared\Transfer\ApiCollectionTransfer
     */
    public function execute($resource, $method, $id, array $params)
    {
        foreach ($this->pluginCollection as $plugin) {
            if (mb_strtolower($plugin->getResourceName()) !== mb_strtolower($resource)) {
                continue;
            }

            if ($method === ApiConfig::ACTION_OPTIONS) {
                return $this->getOptions($plugin, $id, $params);
            }

            if ($plugin instanceof AuthInterface) {
                $plugin->checkAuth($params[ApiConstants::API_REQUEST_TRANSFER_PARAM_KEY]);
            }

            /** @var \Generated\Shared\Transfer\ApiItemTransfer|\Generated\Shared\Transfer\ApiCollectionTransfer $responseTransfer */
            $responseTransfer = call_user_func_array([$plugin, $method], $params);
            $apiOptionsTransfer = $this->getOptions($plugin, $id, $params);
            $responseTransfer->setOptions($apiOptionsTransfer->getOptions());

            if ($plugin instanceof GetTransformerTypeInterface) {
                 $responseTransfer->setTransformerType($plugin->getTransformerType());
            }

            return $responseTransfer;
        }

        throw new ApiDispatchingException(sprintf(
            'Unsupported method "%s" for resource "%s"',
            $method,
            $resource
        ));
    }
}
