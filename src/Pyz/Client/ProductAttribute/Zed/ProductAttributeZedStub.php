<?php

namespace Pyz\Client\ProductAttribute\Zed;

use Generated\Shared\Transfer\ProductAttributeKeysCollectionTransfer;
use Spryker\Client\ZedRequest\ZedRequestClientInterface;

class ProductAttributeZedStub implements ProductAttributeZedStubInterface
{
    /**
     * @var ZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param ZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /**
     * @param ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
     *
     * @return ProductAttributeKeysCollectionTransfer
     */
    public function getKeysToShowOnPdp(
        ProductAttributeKeysCollectionTransfer $productAttributeKeysCollectionTransfer
    ): ProductAttributeKeysCollectionTransfer {
        return $this->zedRequestClient->call(
            '/product-attribute/gateway/get-keys-to-show-on-pdp',
            $productAttributeKeysCollectionTransfer
        );
    }

}
