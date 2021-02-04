<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductUrlWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \Pyz\Yves\ProductUrlWidget\ProductUrlWidgetFactory getFactory()
 */
class ProductUrlWidget extends AbstractWidget
{
    public const NAME = 'ProductUrlWidget';

    /**
     * @param bool|null $isAffiliate
     * @param array $affiliateData
     */
    public function __construct(?bool $isAffiliate, array $affiliateData)
    {
        $this->addParameter('url', $this->getProductUrl($isAffiliate, $affiliateData));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return self::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductUrlWidget/views/product-url/product-url.twig';
    }

    /**
     * @param bool|null $isAffiliate
     * @param array $affiliateData
     *
     * @return string
     */
    protected function getProductUrl(?bool $isAffiliate, array $affiliateData)
    {
        //@TODO just return the url if there is only one affiliate
        if ($isAffiliate) {
            return $this->getProductAffiliateTrackingUrl($affiliateData);
        }

        return '';
    }

    /**
     * @param mixed[] $affiliateData
     *
     * @return string
     */
    protected function getProductAffiliateTrackingUrl(array $affiliateData): string
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if (!$customerTransfer) {
            return $affiliateData['affiliate_deeplink'];
        }

        return $this->getFactory()->getProductAffiliateService()
            ->generateProductAffiliateTrackingUrl(
                $affiliateData['affiliate_deeplink'],
                $customerTransfer
            );
    }
}
