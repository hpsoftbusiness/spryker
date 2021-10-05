<?php
declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductFeedRestApi;

use Pyz\Shared\MyWorldPayment\MyWorldPaymentConstants;
use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\Application\ApplicationConstants;

class ProductFeedRestApiConfig extends AbstractBundleConfig
{
    public const RESOURCE_PRODUCT_FEED = 'product-feed';
    public const RESOURCE_REGULAR_PRODUCT_FEED = 'regular-product-feed';
    public const RESOURCE_BENEFIT_VOUCHER_PRODUCT_FEED = 'benefit-voucher-product-feed';
    public const RESOURCE_SHOPPING_POINT_PRODUCT_FEED = 'shopping-point-product-feed';
    public const RESOURCE_ELITE_CLUB_PRODUCT_FEED = 'elite-club-product-feed';
    public const RESOURCE_ONE_SENSE_PRODUCT_FEED = 'one-sense-product-feed';
    public const RESOURCE_LYCONET_PRODUCT_FEED = 'lyconet-product-feed';
    public const RESOURCE_FEATURED_PRODUCT_FEED = 'featured-product-feed';
    public const RESOURCE_ONLY_ELITE_CLUB_DEAL_PRODUCT_FEED = 'only-elite-club-deal-product-feed';
    public const RESPONSE_CODE_PARAMETER_MUST_BE_INTEGER = '503';
    public const ERROR_MESSAGE_PARAMETER_MUST_BE_INTEGER = 'Value of %s must be of type integer.';

    public const RESOURCE_TYPE_TO_TITLE_MAP = [
        ProductFeedRestApiConfig::RESOURCE_REGULAR_PRODUCT_FEED => 'products',
        ProductFeedRestApiConfig::RESOURCE_BENEFIT_VOUCHER_PRODUCT_FEED => 'bvdeals',
        ProductFeedRestApiConfig::RESOURCE_SHOPPING_POINT_PRODUCT_FEED => 'spdeals',
        ProductFeedRestApiConfig::RESOURCE_ELITE_CLUB_PRODUCT_FEED => 'elite-club',
        ProductFeedRestApiConfig::RESOURCE_ONE_SENSE_PRODUCT_FEED => 'one-sense',
        ProductFeedRestApiConfig::RESOURCE_LYCONET_PRODUCT_FEED => 'lyconet',
        ProductFeedRestApiConfig::RESOURCE_FEATURED_PRODUCT_FEED => 'featured-products',
        ProductFeedRestApiConfig::RESOURCE_ONLY_ELITE_CLUB_DEAL_PRODUCT_FEED => 'elite-club-ec-deal-only',
    ];

    /**
     * @api
     *
     * @return string[]
     */
    public function getIntegerRequestParameterNames(): array
    {
        return [
            'page.limit',
            'page.offset',
        ];
    }

    /**
     * @return string
     */
    public function getYvesHost(): string
    {
        return $this->get(ApplicationConstants::BASE_URL_YVES);
    }

    /**
     * @return string
     */
    public function getShoppingPointsAmountKey(): string
    {
        return $this->get(MyWorldPaymentConstants::PRODUCT_ATTRIBUTE_KEY_SHOPPING_POINTS_AMOUNT);
    }
}
