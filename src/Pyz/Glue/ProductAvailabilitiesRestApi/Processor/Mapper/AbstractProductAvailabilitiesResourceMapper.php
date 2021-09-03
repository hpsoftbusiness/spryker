<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\AbstractProductAvailabilitiesResourceMapper as SprykerAbstractProductAvailabilitiesResourceMapper;

class AbstractProductAvailabilitiesResourceMapper extends SprykerAbstractProductAvailabilitiesResourceMapper
{
    use BenefitProductHelperTrait;

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    private $productStorageClient;

    /**
     * @var \Spryker\Client\Locale\LocaleClientInterface
     */
    private $localeClient;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\Locale\LocaleClientInterface $localeClient
     */
    public function __construct(
        ProductStorageClientInterface $productStorageClient,
        LocaleClientInterface $localeClient
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer
     * @param \Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer $restAbstractProductAvailabilityAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestAbstractProductAvailabilityAttributesTransfer
     */
    public function mapProductAbstractAvailabilityTransferToRestAbstractProductAvailabilityAttributesTransfer(
        ProductAbstractAvailabilityTransfer $productAbstractAvailabilityTransfer,
        RestAbstractProductAvailabilityAttributesTransfer $restAbstractProductAvailabilityAttributesTransfer
    ): RestAbstractProductAvailabilityAttributesTransfer {
        $productViewTransfer = $this->getProductViewTransfer(
            $productAbstractAvailabilityTransfer->getIdProductAbstract()
        );

        return $restAbstractProductAvailabilityAttributesTransfer
            ->fromArray($productAbstractAvailabilityTransfer->toArray(), true)
            ->setQuantity($productAbstractAvailabilityTransfer->getAvailability())
            ->setAvailability($this->isProductAbstractAvailable($productAbstractAvailabilityTransfer))
            ->setCashbackAmount(
                $this->getCashbackAmount($productViewTransfer)
            )
            ->setShoppingPoints(
                $this->getShoppingPointsAmount($productViewTransfer)
            );
    }

    /**
     * @param int $idProductAbstract
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    private function getProductViewTransfer(
        int $idProductAbstract
    ): ?ProductViewTransfer {
        return $this->productStorageClient->findProductAbstractViewTransfer(
            $idProductAbstract,
            $this->localeClient->getCurrentLocale()
        );
    }
}
