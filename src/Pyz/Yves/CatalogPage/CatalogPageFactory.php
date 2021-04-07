<?php

namespace Pyz\Yves\CatalogPage;

use Pyz\Yves\CatalogPage\ChildrenFinder\CategoryChildrenFinder;
use Pyz\Yves\CatalogPage\ChildrenFinder\CategoryChildrenFinderInterface;
use SprykerShop\Yves\CatalogPage\CatalogPageFactory as SprykerCatalogPageFactory;
use Pyz\Yves\CatalogPage\Twig\CatalogPageTwigExtension;

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
     * @return CategoryChildrenFinderInterface
     */
    public function createCategoryChildrenFinder(): CategoryChildrenFinderInterface
    {
        return new CategoryChildrenFinder();
    }
}
