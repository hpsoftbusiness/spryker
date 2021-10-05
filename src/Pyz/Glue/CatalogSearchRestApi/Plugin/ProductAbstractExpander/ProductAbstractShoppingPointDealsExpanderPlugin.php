<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Generated\Shared\Transfer\SpDealTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander\Traits\SingularAttributeValueHelperTrait;
use Spryker\Glue\Kernel\AbstractPlugin;

class ProductAbstractShoppingPointDealsExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
    use SingularAttributeValueHelperTrait;

    private const ATTRIBUTE_SHOPPING_POINT_DEAL = 'shopping-point-deals';
    private const ATTRIBUTE_SP_DEAL = 'sp-deal';

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
        $this->setShoppingPointDeals($abstractProductData, $restCatalogSearchAbstractProductsTransfer);
    }

    /**
     * @param array $abstractProductData
     * @param \Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
     *
     * @return void
     */
    private function setShoppingPointDeals(
        array $abstractProductData,
        RestCatalogSearchAbstractProductsTransfer $restCatalogSearchAbstractProductsTransfer
    ): void {
        $shoppingPointDeal = (bool)$this->extractSingularAttributeValue(
            $abstractProductData[self::ATTRIBUTE_SHOPPING_POINT_DEAL] ?? null
        );
        $restCatalogSearchAbstractProductsTransfer->setShoppingPointDeals($shoppingPointDeal);

        if ($shoppingPointDeal && isset($abstractProductData[self::ATTRIBUTE_SP_DEAL])
            && $abstractProductData[self::ATTRIBUTE_SP_DEAL] !== null) {
            $restCatalogSearchAbstractProductsTransfer->setSpDeal(
                (new SpDealTransfer())->fromArray($abstractProductData[self::ATTRIBUTE_SP_DEAL])
            );
        }
    }
}
