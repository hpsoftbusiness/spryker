<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Sso;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\ClientInterface;
use Pyz\Client\Sso\Client\CustomerInformation;
use Pyz\Client\Sso\Client\CustomerInformationInterface;
use Pyz\Client\Sso\Client\UserToken;
use Pyz\Client\Sso\Client\UserTokenInterface;
use Pyz\Client\Sso\Config\ConfigReader;
use Pyz\Client\Sso\Config\ConfigReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;

/**
 * @method \Pyz\Client\Sso\SsoConfig getConfig()
 */
class SsoFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\Sso\Client\UserTokenInterface
     */
    public function createUserTokenClient(): UserTokenInterface
    {
        return new UserToken(
            $this->createGuzzleClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \Pyz\Client\Sso\Client\CustomerInformationInterface
     */
    public function createCustomerInformationClient(): CustomerInformationInterface
    {
        return new CustomerInformation(
            $this->createGuzzleClient(),
            $this->getConfig()
        );
    }

    /**
     * @return \GuzzleHttp\ClientInterface
     */
    public function createGuzzleClient(): ClientInterface
    {
        return new GuzzleClient();
    }

    /**
     * @return \Pyz\Client\Sso\Config\ConfigReaderInterface
     */
    public function createConfigReader(): ConfigReaderInterface
    {
        return new ConfigReader($this->getConfig());
    }
}
