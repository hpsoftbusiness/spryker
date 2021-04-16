<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CatalogPage\Twig;

use Generated\Shared\Transfer\CategoryNodeStorageTransfer;
use Generated\Shared\Transfer\FacetSearchResultTransfer;
use Pyz\Yves\CatalogPage\ChildrenFinder\CategoryChildrenFinderInterface;
use SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGeneratorInterface;
use SprykerShop\Yves\CatalogPage\Twig\CatalogPageTwigExtension as SprykerCatalogPageTwigExtension;
use Twig\TwigFunction;

class CatalogPageTwigExtension extends SprykerCatalogPageTwigExtension
{
    public const FUNCTION_EXISTS_CATEGORY_CHILD = 'existsCategoryChild';

    /**
     * @var \Pyz\Yves\CatalogPage\ChildrenFinder\CategoryChildrenFinderInterface
     */
    protected $categoryChildrenFinder;

    /**
     * @param \SprykerShop\Yves\CatalogPage\ActiveSearchFilter\UrlGeneratorInterface $activeSearchFilterUrlGenerator
     * @param \Pyz\Yves\CatalogPage\ChildrenFinder\CategoryChildrenFinderInterface $categoryChildrenFinder
     */
    public function __construct(
        UrlGeneratorInterface $activeSearchFilterUrlGenerator,
        CategoryChildrenFinderInterface $categoryChildrenFinder
    ) {
        parent::__construct($activeSearchFilterUrlGenerator);
        $this->categoryChildrenFinder = $categoryChildrenFinder;
    }

    /**
     * @return \Twig\TwigFunction[]
     */
    public function getFunctions()
    {
        return array_merge(
            parent::getFunctions(),
            [
                new TwigFunction(
                    static::FUNCTION_EXISTS_CATEGORY_CHILD,
                    [$this, static::FUNCTION_EXISTS_CATEGORY_CHILD]
                ),
            ]
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CategoryNodeStorageTransfer $categoryNode
     * @param \Generated\Shared\Transfer\FacetSearchResultTransfer $filter
     * @param bool|null $isEmptyCategoryFilterValueVisible
     *
     * @return bool
     */
    public function existsCategoryChild(
        CategoryNodeStorageTransfer $categoryNode,
        FacetSearchResultTransfer $filter,
        ?bool $isEmptyCategoryFilterValueVisible
    ) {
        return $this->categoryChildrenFinder->existsCategoryChild(
            $categoryNode,
            $filter,
            (bool)$isEmptyCategoryFilterValueVisible
        );
    }
}
