<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\ProductUrlWidget\Widget;

use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Pyz\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \Pyz\Yves\ProductUrlWidget\ProductUrlWidgetFactory getFactory()
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
     * @param int|null $abstractProduct
     * @param bool $isConcrete
     */
    public function __construct(
        ?bool $isAffiliate,
        array $affiliateData,
        ?int $abstractProduct = null,
        $isConcrete = false
    ) {
        if ($isAffiliate) {
            $this->hasOneOffer($abstractProduct);
        }

        $this->addParameter('url', $this->getProductUrl($isAffiliate, $affiliateData, $isConcrete));
        $this->addParameter('targetBlank', $this->getTargetBlank($isAffiliate, $isConcrete));
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
     * @param bool $isConcrete
     *
     * @return string
     */
    protected function getProductUrl(?bool $isAffiliate, array $affiliateData, bool $isConcrete): string
    {
        if ($isAffiliate) {
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
        $locale = $this->getLocale();
        $abstractProducts = $this->getFactory()->getProductStorageClient()->getProductAbstractViewTransfers(
            [$abstractProductId],
            $locale
        );
        $concretes = [];

        foreach ($abstractProducts as $abstractProduct) {
            $concretes = array_merge(
                $concretes,
                array_keys($abstractProduct->getAttributeMap()->getProductConcreteIds())
            );
        }

        $productOfferCriteriaFilterTransfer = new ProductOfferStorageCriteriaTransfer();
        $productOfferCriteriaFilterTransfer->setProductConcreteSkus(array_values($concretes));

        $offers = $this->getFactory()->getMerchantProductOfferStorageClient()->getProductOffersBySkus(
            $productOfferCriteriaFilterTransfer
        )->getProductOffersStorage();

        $this->hasOneOffer = $offers->count() === 1;
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

            if (!$isConcrete && (!$this->hasOneOffer || !$customerTransfer)) {
                return false;
            }

            return true;
        }

        return false;
    }
}
