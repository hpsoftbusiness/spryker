<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductApi\Communication\Plugin\Api;

use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Pyz\Shared\Api\ApiConstants;
use Pyz\Zed\Api\Business\Auth\AuthInterface;
use Pyz\Zed\Api\Communication\Transformer\GetTransformerTypeInterface;
use RuntimeException;
use Spryker\Zed\Api\ApiConfig;
use Spryker\Zed\Api\Dependency\Plugin\ApiResourcePluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Pyz\Zed\ProductApi\Business\ProductApiFacadeInterface getFacade()
 * @deprecated Please use Glue API instead (Pyz/Glue/ProductFeedRestApi)
 */
abstract class AbstractProductApiResourcePlugin extends AbstractPlugin implements
    ApiResourcePluginInterface,
    AuthInterface,
    GetTransformerTypeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function add(ApiDataTransfer $apiDataTransfer)
    {
        throw new RuntimeException('Add action not implemented on core level', ApiConfig::HTTP_CODE_NOT_FOUND);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $id
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function get($id)
    {
        throw new RuntimeException('Get action not implemented on core level', ApiConfig::HTTP_CODE_NOT_FOUND);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function update($idProductAbstract, ApiDataTransfer $apiDataTransfer)
    {
        throw new RuntimeException('Update action not implemented on core level', ApiConfig::HTTP_CODE_NOT_FOUND);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idProductAbstract
     *
     * @throws \RuntimeException
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function remove($idProductAbstract)
    {
        throw new RuntimeException('Remove action not implemented on core level', ApiConfig::HTTP_CODE_NOT_FOUND);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiCollectionTransfer|\Generated\Shared\Transfer\ApiItemTransfer
     */
    public function find(ApiRequestTransfer $apiRequestTransfer)
    {
        $this->getFacade()->validateLanguage($apiRequestTransfer);

        return $this->getFacade()->findProducts($apiRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return void
     */
    public function checkAuth(ApiRequestTransfer $apiRequestTransfer): void
    {
        $this->getFacade()->checkAuth(ApiConstants::X_SPRYKER_API_KEY, $apiRequestTransfer);
    }

    /**
     * @return string
     */
    public function getTransformerType(): string
    {
        return ApiConstants::TRANSFORMER_SIMPLE;
    }
}
