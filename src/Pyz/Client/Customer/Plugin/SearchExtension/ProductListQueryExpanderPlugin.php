<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Client\Customer\Plugin\SearchExtension;

use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\Terms;
use Generated\Shared\Search\PageIndexMap;
use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use InvalidArgumentException;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryExpanderPluginInterface;
use Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface;

/**
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class ProductListQueryExpanderPlugin extends AbstractPlugin implements QueryExpanderPluginInterface
{
    /**
     * @var bool
     */
    protected static $customerRequested = false;

    /**
     * @var \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected static $customerTransfer;

    /**
     * @api
     *
     * @param \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface $searchQuery
     * @param array $requestParameters
     *
     * @return \Spryker\Client\SearchExtension\Dependency\Plugin\QueryInterface
     */
    public function expandQuery(QueryInterface $searchQuery, array $requestParameters = []): QueryInterface
    {
        $query = $searchQuery->getSearchQuery();

        $this->expandQueryWithBlacklistFilter($query);
        $this->expandQueryWithWhitelistFilter($query);

        return $searchQuery;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function expandQueryWithBlacklistFilter(Query $query): void
    {
        $blacklistIds = $this->getBlacklistIds();
        if (count($blacklistIds)) {
            $boolQuery = $this->getBoolQuery($query);
            $boolQuery->addMustNot($this->createBlacklistTermQuery($blacklistIds));
        }
    }

    /**
     * @param \Elastica\Query $query
     *
     * @return void
     */
    protected function expandQueryWithWhitelistFilter(Query $query): void
    {
        $whitelistIds = $this->getWhitelistIds();

        if (count($whitelistIds)) {
            $boolQuery = $this->getBoolQuery($query);
            $boolQuery->addFilter($this->createWhitelistTermQuery($whitelistIds));
        }
    }

    /**
     * @param array $blacklistIds
     *
     * @return \Elastica\Query\Terms
     */
    protected function createBlacklistTermQuery(array $blacklistIds): Terms
    {
        return new Terms(PageIndexMap::PRODUCT_LISTS_BLACKLISTS, $blacklistIds);
    }

    /**
     * @param array $whitelistIds
     *
     * @return \Elastica\Query\Terms
     */
    protected function createWhitelistTermQuery(array $whitelistIds): Terms
    {
        return new Terms(PageIndexMap::PRODUCT_LISTS_WHITELISTS, $whitelistIds);
    }

    /**
     * @return int[]
     */
    protected function getBlacklistIds(): array
    {
        $customerProductListCollectionTransfer = $this->findCustomerProductListCollection();

        if (!$customerProductListCollectionTransfer) {
            return [];
        }

        return $customerProductListCollectionTransfer->getBlacklistIds() ?: [];
    }

    /**
     * @return int[]
     */
    protected function getWhitelistIds(): array
    {
        $customerProductListCollectionTransfer = $this->findCustomerProductListCollection();

        if (!$customerProductListCollectionTransfer) {
            return [];
        }

        return $customerProductListCollectionTransfer->getWhitelistIds() ?: [];
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer|null
     */
    protected function findCustomerProductListCollection(): ?CustomerProductListCollectionTransfer
    {
        $customer = $this->getCustomer();

        if ($customer) {
            $customerProductListCollectionTransfer = $customer->getCustomerProductListCollection();

            if ($customerProductListCollectionTransfer) {
                return $customer->getCustomerProductListCollection();
            }
        }

        return $this->getDefaultCustomerProductListCollectionTransfer();
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    protected function getCustomer(): ?CustomerTransfer
    {
        if (!static::$customerRequested) {
            static::$customerRequested = true;
            static::$customerTransfer = $this->getClient()->getCustomer();
        }

        return static::$customerTransfer;
    }

    /**
     * @param \Elastica\Query $query
     *
     * @throws \InvalidArgumentException
     *
     * @return \Elastica\Query\BoolQuery
     */
    protected function getBoolQuery(Query $query): BoolQuery
    {
        $boolQuery = $query->getQuery();
        if (!$boolQuery instanceof BoolQuery) {
            throw new InvalidArgumentException(sprintf(
                'Product List Query Expander available only with %s, got: %s',
                BoolQuery::class,
                get_class($boolQuery)
            ));
        }

        return $boolQuery;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerProductListCollectionTransfer
     */
    protected function getDefaultCustomerProductListCollectionTransfer(): CustomerProductListCollectionTransfer
    {
        return $this->getFactory()
            ->getProductListClient()
            ->getDefaultCustomerProductListCollection();
    }
}
