<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CatalogPage\Controller;

use Spryker\Client\SearchElasticsearch\Plugin\ResultFormatter\FacetResultFormatterPlugin;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use SprykerShop\Yves\CatalogPage\Controller\CatalogController as SprykerCatalogController;

class CatalogController extends SprykerCatalogController
{
    /**
     * @param array $searchResults
     *
     * @return array
     */
    protected function filterFacetsInSearchResults(array $searchResults): array
    {
        $searchResults[FacetResultFormatterPlugin::NAME] = array_filter(
            $searchResults[FacetResultFormatterPlugin::NAME],
            static function (AbstractTransfer $facetResultTransfer): bool {
                /**
                 * @var \Generated\Shared\Transfer\FacetConfigTransfer|null $config
                 * @var \Generated\Shared\Transfer\RangeSearchResultTransfer|\Generated\Shared\Transfer\FacetSearchResultTransfer $facetResultTransfer
                 */
                $config = $facetResultTransfer->getConfig();
                if (!$facetResultTransfer->getConfig()) {
                    return true;
                }

                return !$config->getIsInternal();
            }
        );

        return parent::filterFacetsInSearchResults($searchResults);
    }
}
