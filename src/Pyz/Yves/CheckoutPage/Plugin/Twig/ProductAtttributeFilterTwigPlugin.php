<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Pyz\Yves\CheckoutPage\Plugin\Twig;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFilter;

class ProductAtttributeFilterTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    public const FILTER_NAME_PRODUCT_ATTRIBUTE_FILTER = 'filterAttributes';

    protected static $main_attributes = [
        'brand',
        'color',
        'size',
    ];

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig->addFilter($this->getProductAttributeFilter());

        return $twig;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getProductAttributeFilter(): TwigFilter
    {
        return new TwigFilter(static::FILTER_NAME_PRODUCT_ATTRIBUTE_FILTER, function (array $attributesToFilter) {
            return $this->getFilteredAttributes($attributesToFilter);
        });
    }

    /**
     * @param array $attributes
     *
     * @return array
     */
    protected function getFilteredAttributes(array $attributes): array
    {
        foreach (array_keys($attributes) as $attributeKey) {
            if (!in_array($attributeKey, static::$main_attributes)) {
                unset($attributes[$attributeKey]);
            }
        }

        return $attributes;
    }
}
