<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\Country\Plugin\EventDispatcher;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Yves\Locale\Plugin\Locale\LocaleLocalePlugin;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Service\Container\Exception\NotFoundException;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Pyz\Yves\Country\CountryFactory getFactory()
 * @method \Pyz\Yves\Country\CountryConfig getConfig()()
 */
class CountryEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    private const SERVICE_USER = 'user';
    private const DEFAULT_ENG_LANGUAGE = 'en';

    /**
     * Specification:
     * - Resolves and sets customer's country to store settings (Store singleton).
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(
            KernelEvents::REQUEST,
            function (RequestEvent $event) use ($container) {
                $customerTransfer = $this->getCustomerTransfer($container);
                if ($customerTransfer && $customerTransfer->getCountryId()) {
                    $this->setCurrentCountry($customerTransfer->getCountryId());

                    return;
                }

                try {
                    $acceptLanguageValue = $container->get(LocaleLocalePlugin::HEADER_ACCEPT_LANGUAGE);
                    if (!$acceptLanguageValue || $acceptLanguageValue === self::DEFAULT_ENG_LANGUAGE) {
                        return;
                    }

                    $countryCode = $this->resolveCountryByLanguage($acceptLanguageValue);
                    if ($countryCode) {
                        $this->setCurrentCountry($countryCode);
                    }
                } catch (NotFoundException $exception) {
                    return;
                }
            }
        );

        return $eventDispatcher;
    }

    /**
     * @param string $languageCode
     *
     * @return string|null
     */
    private function resolveCountryByLanguage(string $languageCode): ?string
    {
        $locales = $this->getFactory()->getStore()->getLocales();
        if (!isset($locales[$languageCode])) {
            return null;
        }

        return $this->getFactory()->getLocaleClient()->findMappedCountryCodeByLocale($locales[$languageCode]);
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    private function getCustomerTransfer(ContainerInterface $container): ?CustomerTransfer
    {
        /**
         * @var \SprykerShop\Yves\CustomerPage\Security\Customer $customer
         */
        $customer = $container->get(self::SERVICE_USER);

        return $customer ? $customer->getCustomerTransfer() : null;
    }

    /**
     * @param string $countryIsoCode
     *
     * @return void
     */
    private function setCurrentCountry(string $countryIsoCode): void
    {
        if ($this->isCountrySupported($countryIsoCode)) {
            $this->getFactory()->getStore()->setCurrentCountry($countryIsoCode);
        }
    }

    /**
     * @param string $countryCode
     *
     * @return bool
     */
    private function isCountrySupported(string $countryCode): bool
    {
        $storeCountries = $this->getFactory()->getStore()->getCountries();

        return in_array(strtoupper($countryCode), $storeCountries);
    }
}
