<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Processor\Mapper;

use Generated\Shared\Transfer\RestCatalogSearchSuggestionAbstractProductsTransfer;
use Generated\Shared\Transfer\RestCatalogSearchSuggestionsAttributesTransfer;
use Pyz\Glue\CatalogSearchRestApi\Helper\CatalogCustomFieldsHelper;
use Pyz\Glue\CatalogSearchRestApi\Helper\CatalogCustomFieldsHelperInterface;
use Spryker\Glue\CatalogSearchRestApi\Processor\Mapper\CatalogSearchSuggestionsResourceMapper as SprykerCatalogSearchSuggestionsResourceMapper;

class CatalogSearchSuggestionsResourceMapper extends SprykerCatalogSearchSuggestionsResourceMapper
{
    /**
     * @var \Pyz\Glue\CatalogSearchRestApi\Helper\CatalogCustomFieldsHelperInterface
     */
    private $customFieldsHelper;

    /**
     * @param \Pyz\Glue\CatalogSearchRestApi\Helper\CatalogCustomFieldsHelperInterface $customFieldsHelper
     */
    public function __construct(CatalogCustomFieldsHelperInterface $customFieldsHelper)
    {
        $this->customFieldsHelper = $customFieldsHelper;
    }

    /**
     * @param array $restSearchResponse
     * @param \Generated\Shared\Transfer\RestCatalogSearchSuggestionsAttributesTransfer $restSearchSuggestionsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCatalogSearchSuggestionsAttributesTransfer
     */
    protected function mapSearchSuggestionProductsToRestCatalogSearchSuggestionsAttributesTransfer(
        array $restSearchResponse,
        RestCatalogSearchSuggestionsAttributesTransfer $restSearchSuggestionsAttributesTransfer
    ): RestCatalogSearchSuggestionsAttributesTransfer {
        if (!$this->checkSuggestionByTypeValues($restSearchResponse, static::SEARCH_RESPONSE_PRODUCT_ABSTRACT_KEY)) {
            return $restSearchSuggestionsAttributesTransfer;
        }

        $restSearchResponseSuggest = $restSearchResponse[static::SEARCH_RESPONSE_SUGGESTION_BY_TYPE_KEY];
        $restSearchResponseSuggestProducts = $restSearchResponseSuggest[static::SEARCH_RESPONSE_PRODUCT_ABSTRACT_KEY];

        foreach ($restSearchResponseSuggestProducts as $restSearchResponseSuggestProduct) {
            $isZeroPoints =
                $this->isZeroCashbackAndShoppingPoints($restSearchResponseSuggestProduct[CatalogCustomFieldsHelper::KEY_ATTRIBUTES]);

            $restSearchResponseSuggestProduct[CatalogCustomFieldsHelper::KEY_CASHBACK_AMOUNT] = $isZeroPoints
                ? 0
                : $this->customFieldsHelper->prepareCustomArrayData(
                    $restSearchResponseSuggestProduct[CatalogCustomFieldsHelper::KEY_ATTRIBUTES][CatalogCustomFieldsHelper::KEY_CASHBACK_AMOUNT] ?? 0
                );
            $restSearchResponseSuggestProduct[CatalogCustomFieldsHelper::KEY_SHOPPING_POINTS] = $isZeroPoints
                ? 0
                : $this->customFieldsHelper->prepareCustomArrayData(
                    $restSearchResponseSuggestProduct[CatalogCustomFieldsHelper::KEY_ATTRIBUTES][CatalogCustomFieldsHelper::KEY_SHOPPING_POINTS] ?? 0
                );
            $restCatalogSearchSuggestionAbstractProducts = new RestCatalogSearchSuggestionAbstractProductsTransfer();
            $restCatalogSearchSuggestionAbstractProducts->fromArray(
                $restSearchResponseSuggestProduct,
                true
            );

            $restSearchSuggestionsAttributesTransfer->addAbstractProduct($restCatalogSearchSuggestionAbstractProducts);
        }

        return $restSearchSuggestionsAttributesTransfer;
    }

    /**
     * @param array $productAttributes
     *
     * @return bool
     */
    protected function isZeroCashbackAndShoppingPoints(array $productAttributes): bool
    {
        return (bool)($productAttributes[CatalogCustomFieldsHelper::KEY_SHOPPING_POINT_STORE] ?? false)
            || (bool)($productAttributes[CatalogCustomFieldsHelper::KEY_BENEFIT_STORE] ?? false);
    }
}
