<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductUrlWidget\Widget;

use Pyz\Service\ProductAffiliate\Generator\ProductAffiliateLinkGenerator;
use Pyz\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \Pyz\Yves\ProductUrlWidget\ProductUrlWidgetFactory getFactory()
 * @method \Pyz\Yves\ProductUrlWidget\ProductUrlWidgetConfig getConfig()
 */
class ProductUrlWidget extends AbstractWidget
{
    public const NAME = 'ProductUrlWidget';

    /**
     * @var bool
     */
    private $hasOneOffer = false;

    /**
     * @param bool|null $isAffiliate
     * @param array $affiliateData
     * @param array|null $productAttributes
     * @param int|null $abstractProduct
     * @param bool $isConcrete
     */
    public function __construct(
        ?bool $isAffiliate,
        array $affiliateData,
        ?array $productAttributes = [],
        ?int $abstractProduct = null,
        $isConcrete = false
    ) {
        if ($isAffiliate) {
            $this->hasOneOffer($abstractProduct);
        }

        $this->addParameter('url', $this->getProductUrl($isAffiliate, $affiliateData, $productAttributes, $isConcrete));
        $this->addParameter('targetBlank', $this->getTargetBlank($isAffiliate, $isConcrete));
        $this->addParameter('isAffiliate', $isAffiliate ?: false);
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
     * @param array $productAttributes
     * @param bool $isConcrete
     *
     * @return string
     */
    protected function getProductUrl(
        ?bool $isAffiliate,
        array $affiliateData,
        array $productAttributes,
        bool $isConcrete
    ): string {
        if ($isAffiliate) {
            if (!empty($productAttributes)) {
                $affiliateData[ProductAffiliateLinkGenerator::KEY_OFFICE_DEALER_ID] =
                    $productAttributes[ProductAffiliateLinkGenerator::KEY_OFFICE_DEALER_ID] ?? null;
            }

            if ($this->hasOneOffer || $isConcrete) {
                return $this->getProductAffiliateTrackingUrl($affiliateData);
            }
        }

        return '';
    }

    /**
     * @param array $affiliateData
     *
     * @return string
     */
    protected function getProductAffiliateTrackingUrl(array $affiliateData): string
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        if (!$customerTransfer) {
            return CustomerPageRouteProviderPlugin::ROUTE_NAME_LOGIN;
        }

        return $this->getFactory()->getProductAffiliateService()
            ->generateProductAffiliateTrackingUrl(
                $affiliateData,
                $customerTransfer
            );
    }

    /**
     * @param int|null $abstractProductId
     *
     * @return void
     */
    protected function hasOneOffer(?int $abstractProductId): void
    {
        if ($abstractProductId === null) {
            $this->hasOneOffer = false;

            return;
        }

        $productOfferStorageCollectionTransfer = $this->getFactory()
            ->getProductAbstractOffersClient()
            ->getProductOffersByAbstractId($abstractProductId, $this->getLocale());

        $this->hasOneOffer = $productOfferStorageCollectionTransfer->getProductOffersStorage()->count() === 1;
    }

    /**
     * @param bool|null $isAffiliate
     * @param bool $isConcrete
     *
     * @return bool
     */
    private function getTargetBlank(?bool $isAffiliate, bool $isConcrete): bool
    {
        if ($isAffiliate) {
            if (!$this->hasOneOffer) {
                return false;
            }
            $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

            if (!$isConcrete && !$customerTransfer) {
                return false;
            }

            return true;
        }

        return false;
    }
}
