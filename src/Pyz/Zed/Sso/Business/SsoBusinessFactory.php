<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sso\Business;

use GuzzleHttp\ClientInterface;
use Pyz\Zed\Sso\Business\AccessToken\AccessToken;
use Pyz\Zed\Sso\Business\AccessToken\AccessTokenInterface;
use Pyz\Zed\Sso\SsoDependencyProvider;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Pyz\Zed\Sso\SsoConfig getConfig()
 */
class SsoBusinessFactory extends AbstractBusinessFactory
{
    public function createAccessToken(): AccessTokenInterface
    {
        return new AccessToken(
            $this->getGuzzleClient(),
            $this->getConfig(),
            $this->getErrorLogger()
        );
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function getGuzzleClient(): ClientInterface
    {
        return $this->getProvidedDependency(SsoDependencyProvider::GUZZLE_CLIENT);
    }

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    public function getErrorLogger(): ErrorLoggerInterface
    {
        return $this->getProvidedDependency(SsoDependencyProvider::ERROR_LOGGER);
    }
}
