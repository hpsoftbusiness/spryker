<?php

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
     * @var CategoryChildrenFinderInterface
     */
    protected $categoryChildrenFinder;

    /**
     * @param UrlGeneratorInterface $activeSearchFilterUrlGenerator
     * @param CategoryChildrenFinderInterface $categoryChildrenFinder
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
                )
            ]
        );
    }

    /**
     * @param CategoryNodeStorageTransfer $categoryNode
     * @param FacetSearchResultTransfer $filter
     * @param bool|null $isEmptyCategoryFilterValueVisible
     *
     * @return bool
     */
    public function existsCategoryChild(
        CategoryNodeStorageTransfer $categoryNode,
        FacetSearchResultTransfer $filter,
        ?bool $isEmptyCategoryFilterValueVisible
    )
    {
        return $this->categoryChildrenFinder->existsCategoryChild(
            $categoryNode,
            $filter,
            (bool)$isEmptyCategoryFilterValueVisible
        );
    }
}
