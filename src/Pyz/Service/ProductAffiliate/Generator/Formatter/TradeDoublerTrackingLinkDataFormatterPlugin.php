<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Service\ProductAffiliate\Generator\Formatter;

use Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException;

class TradeDoublerTrackingLinkDataFormatterPlugin extends AbstractTrackingLinkDataFormatterPlugin
{
    protected const AFFILIATE_PARTNER_NAME = 'TRADEDOUBLER';
    protected const DEEPLINK_ADVERTISER_ID_PARAMETER = 'p';

    /**
     * @param string $productAffiliateDeepLink
     *
     * @return string
     */
    protected function getUrl(string $productAffiliateDeepLink): string
    {
        return sprintf('%sepi({TrackingHash})', urldecode($productAffiliateDeepLink));
    }

    /**
     * @return string
     */
    protected function getNetwork(): string
    {
        return 'TDP_{countryId}';
    }

    /**
     * @param string $productAffiliateDeeplink
     *
     * @throws \Pyz\Service\ProductAffiliate\Generator\Exception\ProductAffiliateTrackingLinkGeneratorException
     *
     * @return string
     */
    protected function getAdvertiserId(string $productAffiliateDeeplink): string
    {
        preg_match_all('/([^?()]*)\((?:[^()]*)\)/', $productAffiliateDeeplink, $matches, PREG_SET_ORDER);
        $deeplinkArguments = $this->parseParameterValues($matches);
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
     * @param string[][] $parameterList
     *
     * @return string[]
     */
    private function parseParameterValues(array $parameterList): array
    {
        $parsedParameters = [];

        foreach ($parameterList as $parameter) {
            $value = $this->extractParameterValue($parameter[0]);
            $parsedParameters[$parameter[1]] = $value;
        }

        return $parsedParameters;
    }

    /**
     * @param string $parameter
     *
     * @return string
     */
    private function extractParameterValue(string $parameter): string
    {
        preg_match('/\((.*?)\)/', $parameter, $matches);

        return $matches[1];
    }
}
