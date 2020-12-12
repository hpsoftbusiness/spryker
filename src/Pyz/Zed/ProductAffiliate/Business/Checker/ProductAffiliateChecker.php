<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\ProductAffiliate\Business\Checker;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\CartPreCheckResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Spryker\Zed\Product\Business\ProductFacadeInterface;

class ProductAffiliateChecker implements ProductAffiliateCheckerInterface
{
    public const CART_PRE_CHECK_AFFILIATE_FAILED = 'cart.pre.check.affiliate.failed';
    public const SKU_TRANSLATION_PARAMETER = '%sku%';

    protected const SKU_CONCRETE = 'SKU_CONCRETE';

    /**
     * @var \Spryker\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @param \Spryker\Zed\Product\Business\ProductFacadeInterface $productFacade
     */
    public function __construct(ProductFacadeInterface $productFacade)
    {
        $this->productFacade = $productFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartPreCheckResponseTransfer
     */
    public function check(CartChangeTransfer $cartChangeTransfer): CartPreCheckResponseTransfer
    {
        $cartPreCheckResponseTransfer = (new CartPreCheckResponseTransfer())
            ->setIsSuccess(true);

        $skus = $this->getProductSkusFromCartChangeTransfer($cartChangeTransfer);
        $indexedProductConcreteTransfers = $this->getIndexedProductConcretesByProductConcreteSkus($skus[static::SKU_CONCRETE]);

        $errorMessages = new ArrayObject();

        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if (!isset($indexedProductConcreteTransfers[$itemTransfer->getSku()])) {
                $errorMessages[] = $this->createItemIsAffiliateMessageTransfer($itemTransfer->getSku());
                continue;
            }

            $productConcreteTransfer = $indexedProductConcreteTransfers[$itemTransfer->getSku()];

            if ($productConcreteTransfer->getIsAffiliate()) {
                $errorMessages[] = $this->createItemIsAffiliateMessageTransfer($itemTransfer->getSku());
            }
        }

        if ($errorMessages->count()) {
            $cartPreCheckResponseTransfer
                ->setIsSuccess(false)
                ->setMessages($errorMessages);
        }

        return $cartPreCheckResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return string[][]
     */
    protected function getProductSkusFromCartChangeTransfer(CartChangeTransfer $cartChangeTransfer): array
    {
        $skus = [
            static::SKU_CONCRETE => [],
        ];
        foreach ($cartChangeTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getSku()) {
                $skus[static::SKU_CONCRETE][] = $itemTransfer->getSku();
            }
        }

        return $skus;
    }

    /**
     * @param string[] $skus
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getIndexedProductConcretesByProductConcreteSkus(array $skus): array
    {
        if (!$skus) {
            return [];
        }

        $productConcreteTransfers = $this->productFacade->getRawProductConcreteTransfersByConcreteSkus($skus);
        $indexedProductConcreteTransfers = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $indexedProductConcreteTransfers[$productConcreteTransfer->getSku()] = $productConcreteTransfer;
        }

        return $indexedProductConcreteTransfers;
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\MessageTransfer
     */
    protected function createItemIsAffiliateMessageTransfer(string $sku): MessageTransfer
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::CART_PRE_CHECK_AFFILIATE_FAILED);
        $messageTransfer->setParameters([
            static::SKU_TRANSLATION_PARAMETER => $sku,
        ]);

        return $messageTransfer;
    }
}
