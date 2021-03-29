<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\CatalogSearchRestApi\Plugin\ProductAbstractExpander;

use Generated\Shared\Transfer\RestCatalogSearchAbstractProductsTransfer;
use Pyz\Glue\CatalogSearchRestApi\Dependency\Plugin\CatalogSearchAbstractProductExpanderPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;

/**
 * @method \Pyz\Glue\CatalogSearchRestApi\CatalogSearchRestApiConfig getConfig()
 */
class ProductAbstractFullUrlExpanderPlugin extends AbstractPlugin implements CatalogSearchAbstractProductExpanderPluginInterface
{
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
        $productUrl = $restCatalogSearchAbstractProductsTransfer->getUrl();
        $yvesBaseUrl = $this->getConfig()->getYvesHost();
        if (!$productUrl || !$yvesBaseUrl) {
            return;
        }

        $restCatalogSearchAbstractProductsTransfer->setFullUrl(
            $this->getFullProductUrl($yvesBaseUrl, $productUrl)
        );
    }

    /**
     * @param string $yvesBaseUrl
     * @param string $productUrl
     *
     * @return string
     */
    private function getFullProductUrl(string $yvesBaseUrl, string $productUrl): string
    {
        return sprintf('%s%s', $yvesBaseUrl, $productUrl);
    }
}
