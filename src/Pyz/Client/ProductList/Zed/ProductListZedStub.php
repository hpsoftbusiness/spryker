<?php

namespace Pyz\Client\ProductList\Zed;

use Generated\Shared\Transfer\CustomerProductListCollectionTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ProductListZedStub implements ProductListZedStubInterface
{
    /**
     * @var \Spryker\Client\ZedRequest\ZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ZedRequest\ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @return CustomerProductListCollectionTransfer
     */
    public function getDefaultCustomerProductListCollection(): CustomerProductListCollectionTransfer
    {
        return $this->zedRequestClient->call(
            '/product-list/gateway/get-default-customer-product-list-collection',
            new CustomerProductListCollectionTransfer() // transfer object param is needed in call method, so the empty one is passed
        );
    }
}
