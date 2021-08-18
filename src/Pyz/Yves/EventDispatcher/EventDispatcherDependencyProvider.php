<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\EventDispatcher;

use Pyz\Yves\Country\Plugin\EventDispatcher\CountryEventDispatcherPlugin;
use Pyz\Yves\Currency\Plugin\EventDispatcher\CurrencyEventDispatcherPlugin;
use Pyz\Yves\Router\Plugin\EventDispatcher\LocaleStoreRedirectEventDispatcherPlugin;
use Spryker\Yves\Application\Communication\Plugin\EventDispatcher\HeadersSecurityEventDispatcherPlugin;
use Spryker\Yves\EventDispatcher\EventDispatcherDependencyProvider as SprykerEventDispatcherDependencyProvider;
use Spryker\Yves\Http\Plugin\EventDispatcher\CookieEventDispatcherPlugin;
use Spryker\Yves\Http\Plugin\EventDispatcher\FragmentEventDispatcherPlugin;
use Spryker\Yves\Http\Plugin\EventDispatcher\HeaderEventDispatcherPlugin;
use Spryker\Yves\Http\Plugin\EventDispatcher\HstsHeaderEventDispatcher;
use Spryker\Yves\Kernel\Plugin\EventDispatcher\AutoloaderCacheEventDispatcherPlugin;
use Spryker\Yves\Kernel\Plugin\EventDispatcher\RedirectUrlValidationEventDispatcherPlugin;
use Spryker\Yves\Locale\Plugin\EventDispatcher\LocaleEventDispatcherPlugin;
use Spryker\Yves\Monitoring\Plugin\EventDispatcher\MonitoringRequestTransactionEventDispatcherPlugin;
use Spryker\Yves\Router\Plugin\EventDispatcher\RouterListenerEventDispatcherPlugin;
use Spryker\Yves\Router\Plugin\EventDispatcher\RouterLocaleEventDispatcherPlugin;
use Spryker\Yves\Router\Plugin\EventDispatcher\RouterSslRedirectEventDispatcherPlugin;
use Spryker\Yves\Session\Plugin\EventDispatcher\SessionEventDispatcherPlugin;
use Spryker\Yves\Storage\Plugin\EventDispatcher\StorageCacheEventDispatcherPlugin;
use SprykerShop\Yves\ErrorPage\Plugin\EventDispatcher\ErrorPageEventDispatcherPlugin;
use SprykerShop\Yves\SecurityBlockerPage\Plugin\EventDispatcher\SecurityBlockerAgentEventDispatcherPlugin;
use SprykerShop\Yves\SecurityBlockerPage\Plugin\EventDispatcher\SecurityBlockerCustomerEventDispatcherPlugin;
use SprykerShop\Yves\ShopApplication\Plugin\EventDispatcher\ShopApplicationEventDispatcherPlugin;
use SprykerShop\Yves\ShopApplication\Plugin\EventDispatcher\ShopApplicationExceptionEventDispatcherPlugin;
use SprykerShop\Yves\ShopApplication\Plugin\EventDispatcher\ShopApplicationFilterControllerEventDispatcherPlugin;

class EventDispatcherDependencyProvider extends SprykerEventDispatcherDependencyProvider
{
    /**
     * @return \Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface[]
     */
    protected function getEventDispatcherPlugins(): array
    {
        return [
            new ErrorPageEventDispatcherPlugin(),
            new ShopApplicationFilterControllerEventDispatcherPlugin(),
            new ShopApplicationExceptionEventDispatcherPlugin(),
            new ShopApplicationEventDispatcherPlugin(),
            new LocaleEventDispatcherPlugin(),
            new RouterLocaleEventDispatcherPlugin(),
            new HeadersSecurityEventDispatcherPlugin(),
            new StorageCacheEventDispatcherPlugin(),
            new MonitoringRequestTransactionEventDispatcherPlugin(),
            new AutoloaderCacheEventDispatcherPlugin(),
            new CookieEventDispatcherPlugin(),
            new FragmentEventDispatcherPlugin(),
            new HeaderEventDispatcherPlugin(),
            new HstsHeaderEventDispatcher(),
            new RouterListenerEventDispatcherPlugin(),
            new RouterSslRedirectEventDispatcherPlugin(),
            new SessionEventDispatcherPlugin(),
            new RedirectUrlValidationEventDispatcherPlugin(),
            new CurrencyEventDispatcherPlugin(),
            new CountryEventDispatcherPlugin(),
            new SecurityBlockerCustomerEventDispatcherPlugin(),
            new SecurityBlockerAgentEventDispatcherPlugin(),
            new LocaleStoreRedirectEventDispatcherPlugin(),
        ];
    }
}
