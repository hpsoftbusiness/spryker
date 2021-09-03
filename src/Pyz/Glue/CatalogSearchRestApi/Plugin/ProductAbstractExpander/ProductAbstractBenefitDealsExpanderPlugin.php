<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\BvDealTransfer;
use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\Traits\SingularAttributeValueHelperTrait;
use Spryker\Glue\Kernel\AbstractPlugin;

class ProductAbstractBenefitDealsExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    use SingularAttributeValueHelperTrait;

    private const ATTRIBUTE_BENEFIT_DEAL = 'benefit-deals';
    private const ATTRIBUTE_BV_DEAL = 'bv-deal';

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    public function expand(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $this->setBenefitDealsData($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setBenefitDealsData(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $benefitDeal = (bool)$this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_BENEFIT_DEAL] ?? null
        );
        $restCatalogSearchAbstractProductsTransfer->setBenefitDeals($benefitDeal);

        if ($benefitDeal && isset($abstractProductData[self::ATTRIBUTE_BV_DEAL])
            && $abstractProductData[self::ATTRIBUTE_BV_DEAL] !== null) {
            $restCatalogSearchAbstractProductsTransfer->setBvDeal(
                (new BvDealTransfer())->fromArray($abstractProductData[self::ATTRIBUTE_BV_DEAL])
            );
        }
    }
}
