<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace PyzTest\Client\Catalog;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerGroupToProductListTransfer;
use Generated\Shared\Transfer\CustomerGroupTransfer;
use Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer;
use Orm\Zed\CustomerGroup\Persistence\SpyCustomerGroupQuery;
use Orm\Zed\ProductList\Persistence\SpyProductListProductConcreteQuery;
use Spryker\Client\Session\SessionClient;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
 */
class CatalogClientTester extends Actor
{
    use _generated\CatalogClientTesterActions;

    /**
     * @param int $idCustomerGroup
     * @param int $idProductList
     *
     * @return \Generated\Shared\Transfer\CustomerGroupToProductListTransfer
     */
    public function haveCustomerGroupProductList(int $idCustomerGroup, int $idProductList): CustomerGroupToProductListTransfer
    {
        return $this
            ->getLocator()
            ->customerGroupProductList()
            ->facade()
            ->createCustomerGroupProductList(
                (new CustomerGroupToProductListTransfer())
                    ->setIdCustomerGroup($idCustomerGroup)
                    ->setIdProductList($idProductList)
            );
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerGroupTransfer
     */
    public function haveCustomerGroupWithId1(): CustomerGroupTransfer
    {
        $customerGroup = SpyCustomerGroupQuery::create()
            ->filterByIdCustomerGroup(1)
            ->findOneOrCreate();

        if ($customerGroup->isNew()) {
            $customerGroup->setName('Customer Group 1');
            $customerGroup->setDescription('Customer Group 1');
            $customerGroup->save();
        }

        return (new CustomerGroupTransfer())
            ->setIdCustomerGroup(1)
            ->setName($customerGroup->getName())
            ->setDescription($customerGroup->getDescription());
    }

    /**
     * @param int $idProductList
     * @param int $idProduct
     *
     * @return \Generated\Shared\Transfer\ProductListProductConcreteRelationTransfer
     */
    public function haveProductListProduct(int $idProductList, int $idProduct): ProductListProductConcreteRelationTransfer
    {
        $productListProductCategoryEntity = SpyProductListProductConcreteQuery::create()
            ->filterByFkProductList($idProductList)
            ->filterByFkProduct($idProduct)
            ->findOneOrCreate();

        if ($productListProductCategoryEntity->isNew()) {
            $productListProductCategoryEntity->save();
        }

        return (new ProductListProductConcreteRelationTransfer())
            ->setProductIds([$productListProductCategoryEntity->getFkProduct()])
            ->setIdProductList($productListProductCategoryEntity->getFkProductList());
    }

    /**
     * @return void
     */
    public function setUpSession(): void
    {
        (new SessionClient())->setContainer(
            new Session(
                new MockArraySessionStorage()
            )
        );
    }
}
