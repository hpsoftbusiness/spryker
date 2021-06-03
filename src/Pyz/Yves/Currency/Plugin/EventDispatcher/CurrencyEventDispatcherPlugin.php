<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Currency\Plugin\EventDispatcher;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Pyz\Client\Currency\CurrencyClientInterface getClient()
 * @method \Pyz\Yves\Currency\CurrencyFactory getFactory()
 * @method \Pyz\Yves\Currency\CurrencyConfig getConfig()
 */
class CurrencyEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    private const SERVICE_USER = 'user';

    /**
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(
        EventDispatcherInterface $eventDispatcher,
        ContainerInterface $container
    ): EventDispatcherInterface {
        if (!$this->getConfig()->isMultiCurrencyEnabled()) {
            return $eventDispatcher;
        }

        $eventDispatcher->addListener(
            KernelEvents::REQUEST,
            function (RequestEvent $event) use ($container) {
                $localeName = $this->getLocaleName($container);
                $this->getClient()->setCurrentCurrencyByLocale($localeName);
            }
        );

        return $eventDispatcher;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return string
     */
    private function getLocaleName(ContainerInterface $container): string
    {
        $customerTransfer = $this->getCustomerTransfer($container);
        if (!$customerTransfer || !$customerTransfer->getCountryId()) {
            return self::getLocale();
        }

        $mappedLocale = $this->getFactory()->getLocaleClient()->findLocaleRelatedByCountryCode(
            $customerTransfer->getCountryId()
        );

        return $mappedLocale ?: self::getLocale();
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    private function getCustomerTransfer(ContainerInterface $container): ?CustomerTransfer
    {
        /**
         * @var \SprykerShop\Yves\CustomerPage\Security\Customer|null $customer
         */
        $customer = $container->get(self::SERVICE_USER);

        return $customer ? $customer->getCustomerTransfer() : null;
    }
}
