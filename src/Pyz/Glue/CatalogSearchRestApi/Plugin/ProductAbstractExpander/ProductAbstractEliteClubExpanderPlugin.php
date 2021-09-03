<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\Traits\SingularAttributeValueHelperTrait;
use Spryker\Glue\Kernel\AbstractPlugin;

class ProductAbstractEliteClubExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    use SingularAttributeValueHelperTrait;

    private const ATTRIBUTE_ELITE_CLUB_DEAL = 'ec_deals';
    private const ATTRIBUTE_ONLY_ELITE_CLUB_DEAL = 'only_ec_deals';

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
        $this->setEliteClubData($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setEliteClubData(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $eliteClubDeal = $this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_ELITE_CLUB_DEAL] ?? null
        );
        $restCatalogSearchAbstractProductsTransfer->setEliteClub((bool)$eliteClubDeal);
        $onlyEliteClubDeal = $this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_ONLY_ELITE_CLUB_DEAL] ?? null
        );
        $restCatalogSearchAbstractProductsTransfer->setEliteClubOnly((bool)$onlyEliteClubDeal);
    }
}
