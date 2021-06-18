<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Client\Catalog\Client;

use Codeception\TestCase\Test;
use Pyz\Shared\CustomerGroupStorage\CustomerGroupStorageConstants;
use Spryker\Shared\ProductPageSearch\ProductPageSearchConstants;
use Spryker\Shared\ProductSetPageSearch\ProductSetPageSearchConstants;
use Spryker\Shared\Queue\QueueConfig;
use Spryker\Zed\Queue\Communication\Console\QueueWorkerConsole;
use Symfony\Component\Console\Output\NullOutput;

/**
 * Auto-generated group annotations
 *
 * @group PyzTest
 * @group Client
 * @group Catalog
 * @group Client
 * @group ProductSearchVisibilityTest
 * Add your own group annotations below this line
 */
class ProductSearchVisibilityTest extends Test
{
    /**
     * @var \PyzTest\Client\Catalog\CatalogClientTester
     */
    protected $tester;

    /**
     * @var \Pyz\Client\Catalog\CatalogClientInterface
     */
    protected $sut;

    /**
     * @return void
     */
    protected function _before()
    {
        $this->tester->setUpSession();
        $this->sut = $this->tester->getLocator()->catalog()->client();
    }

    /**
     * @return void
     */
    public function testGuestUsersCanSeeAllowedProducts()
    {
        // Arrange
        $taxSet = $this->tester->haveTaxSet();

        $productConcrete = $this->tester->haveFullProduct(
            [],
            [
                'idTaxSet' => $taxSet->getIdTaxSet(),
            ]
        );
        $priceProduct = $this->tester->havePriceProduct([
            'skuProductAbstract' => $productConcrete->getAbstractSku(),
        ]);

        $productList = $this->tester->haveProductList([
            'type' => 'whitelist',
        ]);
        $this->tester->haveProductListProduct(
            $productList->getIdProductList(),
            $productConcrete->getIdProductConcrete()
        );

        $customerGroup = $this->tester->haveCustomerGroupWithId1();
        $this->tester->haveCustomerGroupProductList(
            $customerGroup->getIdCustomerGroup(),
            $productList->getIdProductList()
        );

        $this
            ->tester
            ->getLocator()
            ->customerGroupStorage()
            ->facade()
            ->publish($customerGroup->getIdCustomerGroup());

        $this
            ->tester
            ->getLocator()
            ->productPageSearch()
            ->facade()
            ->publish([$productConcrete->getFkProductAbstract()]);

        $this
            ->tester
            ->getLocator()
            ->synchronization()
            ->facade()
            ->executeResolvedPluginsBySourcesWithIds(
                [ProductPageSearchConstants::PRODUCT_ABSTRACT_RESOURCE_NAME],
                [$productConcrete->getFkProductAbstract()]
            );

        $this
            ->tester
            ->getLocator()
            ->synchronization()
            ->facade()
            ->executeResolvedPluginsBySourcesWithIds(
                [CustomerGroupStorageConstants::CUSTOMER_GROUP_RESOURCE_NAME],
                [$customerGroup->getIdCustomerGroup()]
            );

        $this
            ->tester
            ->getLocator()
            ->queue()
            ->facade()
            ->startTask(ProductSetPageSearchConstants::PRODUCT_SET_SYNC_SEARCH_QUEUE);

        $this
            ->tester
            ->getLocator()
            ->queue()
            ->facade()
            ->startTask(CustomerGroupStorageConstants::CUSTOMER_GROUP_SYNC_STORAGE_QUEUE);

        $this
            ->tester
            ->getLocator()
            ->queue()
            ->facade()
            ->startWorker(
                QueueWorkerConsole::QUEUE_RUNNER_COMMAND,
                new NullOutput(),
                [
                    QueueConfig::CONFIG_WORKER_STOP_WHEN_EMPTY => false,
                ]
            );

        // Act
        $searchResults = $this->sut->catalogSearch($productConcrete->getAbstractSku(), []);

        // Assert
        $this->tester->assertCount(1, $searchResults['products']);
    }
}
