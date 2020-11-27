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
use Pyz\Client\Sso\Client\Mapper\CustomerInformationMapper;
use Pyz\Client\Sso\Client\Mapper\CustomerInformationMapperInterface;
use Pyz\Client\Sso\Client\UserToken;
use Pyz\Client\Sso\Client\UserTokenInterface;
use Pyz\Client\Sso\Client\Validator\CustomerInformationValidator;
use Pyz\Client\Sso\Client\Validator\CustomerInformationValidatorInterface;
use Pyz\Client\Sso\Config\ConfigReader;
use Pyz\Client\Sso\Config\ConfigReaderInterface;
use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Shared\ErrorHandler\ErrorLoggerInterface;

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
            $this->getConfig(),
            $this->getErrorLogger()
        );
    }

    /**
     * @return \Pyz\Client\Sso\Client\CustomerInformationInterface
     */
    public function createCustomerInformationClient(): CustomerInformationInterface
    {
        return new CustomerInformation(
            $this->createGuzzleClient(),
            $this->createCustomerInformationValidator(),
            $this->createCustomerInformationMapper(),
            $this->getConfig(),
            $this->getErrorLogger()
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

    /**
     * @return \Spryker\Shared\ErrorHandler\ErrorLoggerInterface
     */
    public function getErrorLogger(): ErrorLoggerInterface
    {
        return $this->getProvidedDependency(SsoDependencyProvider::ERROR_LOGGER);
    }

    /**
     * @return \Pyz\Client\Sso\Client\Validator\CustomerInformationValidatorInterface
     */
    public function createCustomerInformationValidator(): CustomerInformationValidatorInterface
    {
        return new CustomerInformationValidator();
    }

    /**
     * @return \Pyz\Client\Sso\Client\Mapper\CustomerInformationMapperInterface
     */
    public function createCustomerInformationMapper(): CustomerInformationMapperInterface
    {
        return new CustomerInformationMapper();
    }
}
