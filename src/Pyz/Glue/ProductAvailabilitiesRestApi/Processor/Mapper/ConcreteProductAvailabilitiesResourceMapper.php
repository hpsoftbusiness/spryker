<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Glue\ProductAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Glue\ProductAvailabilitiesRestApi\Processor\Mapper\ConcreteProductAvailabilitiesResourceMapper as SprykerConcreteProductAvailabilitiesResourceMapper;

class ConcreteProductAvailabilitiesResourceMapper extends SprykerConcreteProductAvailabilitiesResourceMapper
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
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     * @param \Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer $restConcreteProductAvailabilityAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestConcreteProductAvailabilityAttributesTransfer
     */
    public function mapProductConcreteAvailabilityTransferToRestConcreteProductAvailabilityAttributesTransfer(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer,
        RestConcreteProductAvailabilityAttributesTransfer $restConcreteProductAvailabilityAttributesTransfer
    ): RestConcreteProductAvailabilityAttributesTransfer {
        $productViewTransfer = $this->getProductViewTransfer(
            $productConcreteAvailabilityTransfer->getIdProductConcrete()
        );

        return $restConcreteProductAvailabilityAttributesTransfer
            ->fromArray($productConcreteAvailabilityTransfer->toArray(), true)
            ->setQuantity($productConcreteAvailabilityTransfer->getAvailability())
            ->setAvailability($this->isProductConcreteAvailable($productConcreteAvailabilityTransfer))
            ->setCashbackAmount(
                $this->getCashbackAmount($productViewTransfer)
            )
            ->setShoppingPoints(
                $this->getShoppingPointsAmount($productViewTransfer)
            );
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer|null
     */
    private function getProductViewTransfer(
        int $idProductConcrete
    ): ?ProductViewTransfer {
        return $this->productStorageClient->findProductConcreteViewTransfer(
            $idProductConcrete,
            $this->localeClient->getCurrentLocale()
        );
    }
}
