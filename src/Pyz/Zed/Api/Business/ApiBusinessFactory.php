<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Api\Business;

use Pyz\Shared\Api\ApiConstants;
use Pyz\Zed\Api\Business\Auth\AuthInterface;
use Pyz\Zed\Api\Business\Auth\XSprykerApiKeyAuth;
use Pyz\Zed\Api\Business\Exception\UnsupportedAuthTypeException;
use Pyz\Zed\Api\Business\Model\Dispatcher;
use Pyz\Zed\Api\Business\Model\ResourceHandler;
use Spryker\Zed\Api\Business\ApiBusinessFactory as SprykerApiBusinessFactory;

class ApiBusinessFactory extends SprykerApiBusinessFactory
{
    /**
     * @return \Spryker\Zed\Api\Business\Model\DispatcherInterface
     */
    public function createDispatcher()
    {
        return new Dispatcher(
            $this->createResourceHandler(),
            $this->createProcessor(),
            $this->createValidator()
        );
    }

    /**
     * @return \Spryker\Zed\Api\Business\Model\ResourceHandlerInterface
     */
    public function createResourceHandler()
    {
        return new ResourceHandler(
            $this->getApiPlugins(),
            $this->getConfig()
        );
    }

    /**
     * @param string $authType
     *
     * @throws \Pyz\Zed\Api\Business\Exception\UnsupportedAuthTypeException
     *
     * @return \Pyz\Zed\Api\Business\Auth\AuthInterface
     */
    public function createAuth(string $authType): AuthInterface
    {
        switch ($authType) {
            case ApiConstants::X_SPRYKER_API_KEY:
                return new XSprykerApiKeyAuth();
            default:
                throw new UnsupportedAuthTypeException($authType);
        }
    }
}
