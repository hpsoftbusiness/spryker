<?php declare(strict_types = 1);

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\SearchElasticsearch\Plugin\QueryExpander;

use Generated\Shared\Search\PageIndexMap;

class IsAffiliateQueryExpanderPlugin extends AbstractBoolQueryExpanderPlugin
{
    protected const BOOL_PARAMETER_INDEXED_FIELD_NAME = PageIndexMap::IS_AFFILIATE;
}
