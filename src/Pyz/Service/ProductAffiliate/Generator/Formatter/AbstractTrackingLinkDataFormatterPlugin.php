<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator\Formatter;

use Generated\Shared\Transfer\CustomerTransfer;
use Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException;
use Pyz\Service\ProductAffiliate\Generator\ProductAffiliateLinkGenerator;
use Spryker\Service\Kernel\AbstractPlugin;

abstract class AbstractTrackingLinkDataFormatterPlugin extends AbstractPlugin implements TrackingLinkDataFormatterPluginInterface
{
    /**
     * Should be overridden - will be used for finding a fitting formatter for affiliate partner.
     */
    protected const AFFILIATE_PARTNER_NAME = '';

    /**
     * Should be overridden with value of a deeplink parameter containing AdvertiserId value.
     */
    protected const DEEPLINK_ADVERTISER_ID_PARAMETER = '';

    protected const PARAMETER_CUSTOMER_NUMBER = 'customerNumber';
    protected const PARAMETER_NETWORK = 'network';
    protected const PARAMETER_ADVERTISER_ID = 'AdvertiserId';
    protected const PARAMETER_URL = 'url';

    protected const EXCEPTION_MISSING_ADVERTISER_ID = 'Product affiliate deeplink url missing \'%s\' parameter for AdvertiserId.';

    /**
     * @param array $affiliateData
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return array
     */
    public function getFormattedTrackingLinkData(array $affiliateData, CustomerTransfer $customerTransfer): array
    {
        return [
            self::PARAMETER_CUSTOMER_NUMBER => $this->getMyWorldCustomerNumber($customerTransfer),
            self::PARAMETER_NETWORK => $this->getNetwork(),
            self::PARAMETER_ADVERTISER_ID => $this->getAdvertiserId($affiliateData),
            self::PARAMETER_URL => $this->getUrl($affiliateData[ProductAffiliateLinkGenerator::KEY_AFFILIATE_DEEPLINK]),
        ];
    }

    /**
     * @return string
     */
    public function getApplicableAffiliatePartnerName(): string
    {
        return static::AFFILIATE_PARTNER_NAME;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return string
     */
    protected function getMyWorldCustomerNumber(CustomerTransfer $customerTransfer): string
    {
        return str_replace('.', '', $customerTransfer->getMyWorldCustomerNumber());
    }

    /**
     * @param array $affiliateData
     *
     * @throws \Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException
     *
     * @return string
     */
    protected function getAdvertiserId(array $affiliateData): string
    {
        $productAffiliateDeeplink = $affiliateData[ProductAffiliateLinkGenerator::KEY_AFFILIATE_DEEPLINK];
        $deeplinkQueryString = parse_url($productAffiliateDeeplink, PHP_URL_QUERY);
        parse_str($deeplinkQueryString, $deeplinkArguments);

        $advertiserId = $deeplinkArguments[static::DEEPLINK_ADVERTISER_ID_PARAMETER] ?? null;
        if ($advertiserId === null) {
            throw new ProductAffiliateTrackingLinkGeneratorException(
                sprintf(
                    self::EXCEPTION_MISSING_ADVERTISER_ID,
                    self::DEEPLINK_ADVERTISER_ID_PARAMETER
                )
            );
        }

        return $advertiserId;
    }

    /**
     * @param string $productAffiliateDeepLink
     *
     * @return string
     */
    abstract protected function getUrl(string $productAffiliateDeepLink): string;

    /**
     * @return string
     */
    abstract protected function getNetwork(): string;
}
