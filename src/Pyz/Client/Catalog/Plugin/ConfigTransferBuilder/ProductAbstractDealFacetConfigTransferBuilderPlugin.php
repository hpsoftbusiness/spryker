<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Catalog\Plugin\ConfigTransferBuilder;

use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\FacetConfigTransfer;
use Pyz\Client\Catalog\CatalogConfig;
use Spryker\Client\Catalog\Dependency\Plugin\FacetConfigTransferBuilderPluginInterface;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Shared\Search\SearchConfig;

class ProductAbstractDealFacetConfigTransferBuilderPlugin extends AbstractPlugin implements FacetConfigTransferBuilderPluginInterface
{
    /**
     * @return \Generated\Shared\Transfer\FacetConfigTransfer
     */
    public function build(): FacetConfigTransfer
    {
        return (new FacetConfigTransfer())
            ->setName(CatalogConfig::PRODUCT_ABSTRACT_DEAL_FACET_NAME)
            ->setParameterName(CatalogConfig::PRODUCT_ABSTRACT_DEAL_FACET_NAME)
            ->setFieldName(PageIndexMap::STRING_FACET)
            ->setIsMultiValued(false)
            ->setType(SearchConfig::FACET_TYPE_ENUMERATION)
            ->setIsInternal(false);
    }
}
