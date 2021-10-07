<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\MyWorldPayment;

use Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface;
use Spryker\Client\Cart\CartClientInterface;
use Spryker\Client\Session\SessionClient;
use Spryker\Shared\Translator\TranslatorInterface;
use Spryker\Yves\Kernel\AbstractFactory;

class MyWorldPaymentFactory extends AbstractFactory
{
    /**
     * @return \Pyz\Client\MyWorldPayment\MyWorldPaymentClientInterface
     */
    public function getMyWorldPaymentClient(): MyWorldPaymentClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CLIENT_MY_WORLD_PAYMENT);
    }

    /**
     * @return \Spryker\Client\Cart\CartClientInterface
     */
    public function getCartClient(): CartClientInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Client\Session\SessionClient
     */
    public function getSessionClient(): SessionClient
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \Spryker\Shared\Translator\TranslatorInterface
     */
    public function getTranslatorService(): TranslatorInterface
    {
        return $this->getProvidedDependency(MyWorldPaymentDependencyProvider::SERVICE_TRANSLATOR);
    }
}
