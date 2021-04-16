<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CatalogPage;

use Pyz\Yves\CatalogPage\ChildrenFinder\CategoryChildrenFinder;
use Pyz\Yves\CatalogPage\ChildrenFinder\CategoryChildrenFinderInterface;
use Pyz\Yves\CatalogPage\Twig\CatalogPageTwigExtension;
use SprykerShop\Yves\CatalogPage\CatalogPageFactory as SprykerCatalogPageFactory;

class CatalogPageFactory extends SprykerCatalogPageFactory
{
    /**
     * @return \Spryker\Shared\Twig\TwigExtension
     */
    public function createProductAbstractReviewTwigExtension()
    {
        return new CatalogPageTwigExtension(
            $this->createActiveSearchFilterUrlGenerator(),
            $this->createCategoryChildrenFinder()
        );
    }

    /**
     * @return \Pyz\Yves\CatalogPage\ChildrenFinder\CategoryChildrenFinderInterface
     */
    public function createCategoryChildrenFinder(): CategoryChildrenFinderInterface
    {
        return new CategoryChildrenFinder();
    }
}
