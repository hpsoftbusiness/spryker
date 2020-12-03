<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Sso\Communication;

use Pyz\Client\Sso\SsoClientInterface;
use Pyz\Zed\Sso\SsoDependencyProvider;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;

class SsoCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Pyz\Client\Sso\SsoClientInterface
     */
    public function getSsoClient(): SsoClientInterface
    {
        return $this->getProvidedDependency(SsoDependencyProvider::CLIENT_SSO);
    }
}
