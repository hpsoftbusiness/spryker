<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CustomerPage\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFilter;

/**
 * @method \Pyz\Yves\CustomerPage\CustomerPageFactory getFactory()
 */
class FormatShoppingPointsFilterTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FILTER_NAME_FORMAT_SHOPPING_POINTS_FILTER = 'formatShoppingPoints';

    /**
     * {@inheritDoc}
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFilter($this->getShoppingPointsFormatFilter());

        return $twig;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getShoppingPointsFormatFilter(): TwigFilter
    {
        return new TwigFilter(
            static::FILTER_NAME_FORMAT_SHOPPING_POINTS_FILTER,
            function (float $shoppingPoints) {
                return $this->getFactory()->getLocaleClient()->formatNumberDueToCountry($shoppingPoints);
            }
        );
    }
}
