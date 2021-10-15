<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\Weclapp\Business\Exporter;

use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\EventEntityTransfer;
use Generated\Shared\Transfer\EventQueueSendMessageBodyTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QueueSendMessageTransfer;
use Pyz\Client\Weclapp\WeclappClientInterface;
use Pyz\Shared\Weclapp\WeclappConfig;
use Pyz\Zed\Country\Business\CountryFacadeInterface;
use Pyz\Zed\Product\Business\ProductFacadeInterface;
use Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface;
use Pyz\Zed\Weclapp\Communication\Plugin\Event\Listener\WeclappProductExportBulkListener;
use Pyz\Zed\Weclapp\Persistence\WeclappEntityManagerInterface;
use Pyz\Zed\Weclapp\Persistence\WeclappRepositoryInterface;
use Spryker\Client\Queue\QueueClientInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Product\Dependency\ProductEvents;

class ProductExporter implements ProductExporterInterface
{
    protected const BATCH_SIZE = 100;

    /**
     * @var \Pyz\Client\Weclapp\WeclappClientInterface
     */
    protected $weclappClient;

    /**
     * @var \Pyz\Zed\Product\Business\ProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface
     */
    protected $productCategoryFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Pyz\Zed\Country\Business\CountryFacadeInterface
     */
    protected $countryFacade;

    /**
     * @var \Pyz\Zed\Weclapp\Persistence\WeclappRepositoryInterface
     */
    protected $weclappRepository;

    /**
     * @var \Pyz\Zed\Weclapp\Persistence\WeclappEntityManagerInterface
     */
    protected $weclappEntityManager;

    /**
     * @var \Spryker\Client\Queue\QueueClientInterface
     */
    protected $queueClient;

    /**
     * @param \Pyz\Client\Weclapp\WeclappClientInterface $weclappClient
     * @param \Pyz\Zed\Product\Business\ProductFacadeInterface $productFacade
     * @param \Pyz\Zed\ProductCategory\Business\ProductCategoryFacadeInterface $productCategoryFacade
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     * @param \Pyz\Zed\Country\Business\CountryFacadeInterface $countryFacade
     * @param \Pyz\Zed\Weclapp\Persistence\WeclappRepositoryInterface $weclappRepository
     * @param \Pyz\Zed\Weclapp\Persistence\WeclappEntityManagerInterface $weclappEntityManager
     * @param \Spryker\Client\Queue\QueueClientInterface $queueClient
     */
    public function __construct(
        WeclappClientInterface $weclappClient,
        ProductFacadeInterface $productFacade,
        ProductCategoryFacadeInterface $productCategoryFacade,
        LocaleFacadeInterface $localeFacade,
        CountryFacadeInterface $countryFacade,
        WeclappRepositoryInterface $weclappRepository,
        WeclappEntityManagerInterface $weclappEntityManager,
        QueueClientInterface $queueClient
    ) {
        $this->weclappClient = $weclappClient;
        $this->productFacade = $productFacade;
        $this->productCategoryFacade = $productCategoryFacade;
        $this->localeFacade = $localeFacade;
        $this->countryFacade = $countryFacade;
        $this->weclappRepository = $weclappRepository;
        $this->weclappEntityManager = $weclappEntityManager;
        $this->queueClient = $queueClient;
    }

    /**
     * @param array $productsIds
     *
     * @return void
     */
    public function exportProducts(array $productsIds): void
    {
        foreach ($this->getProducts($productsIds) as $product) {
            $weclappProduct = $this->weclappClient->getArticle($product);
            if ($weclappProduct) {
                $this->weclappClient->putArticle($product, $weclappProduct);
            } else {
                $this->weclappClient->postArticle($product);
            }
        }
    }

    /**
     * @return void
     */
    public function exportAllProducts(): void
    {
        while ($productsIds = $this->weclappRepository->getExistingProductsIdsToExport(static::BATCH_SIZE)) {
            $this->exportProductsViaQueue($productsIds);
            $this->weclappEntityManager->insertWeclappExports(
                $productsIds,
                WeclappConfig::WECLAPP_EXPORT_TYPE_PRODUCT
            );
        }
    }

    /**
     * @param array $productsIds
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer[]
     */
    protected function getProducts(array $productsIds): array
    {
        $products = $this->productFacade->getProductConcreteTransfersByProductIds($productsIds);
        $locale = $this->localeFacade->getLocale(WeclappConfig::LOCALE_CODE);
        foreach ($products as $key => $product) {
            if ($product->getIsAffiliate() || $product->getIsRemoved()) { // do not send affiliate and removed products
                unset($products[$key]);
                continue;
            }
            $product->setCategoryCollection(
                $this->productCategoryFacade->getCategoryTransferCollectionByIdProductAbstract(
                    $product->getFkProductAbstractOrFail(),
                    $locale
                )
            );
            $product->setCountryOfOrigin($this->getCountryOfOrigin($product));
        }

        return $products;
    }

    /**
     * @param array $productsIds
     *
     * @return void
     */
    protected function exportProductsViaQueue(array $productsIds): void
    {
        $queueSendMessageTransfers = [];

        foreach ($productsIds as $productId) {
            $eventEntityTransfer = new EventEntityTransfer();
            $eventEntityTransfer->setId($productId);
            $eventEntityTransfer->setEvent(ProductEvents::ENTITY_SPY_PRODUCT_CREATE);

            $queueSendMessageTransfer = new QueueSendMessageTransfer();
            $queueSendMessageTransfer->setBody(json_encode([
                EventQueueSendMessageBodyTransfer::LISTENER_CLASS_NAME => WeclappProductExportBulkListener::class,
                EventQueueSendMessageBodyTransfer::TRANSFER_CLASS_NAME => get_class($eventEntityTransfer),
                EventQueueSendMessageBodyTransfer::TRANSFER_DATA => $eventEntityTransfer->toArray(),
                EventQueueSendMessageBodyTransfer::EVENT_NAME => ProductEvents::ENTITY_SPY_PRODUCT_CREATE,
            ]));

            $queueSendMessageTransfers[] = $queueSendMessageTransfer;
        }

        $this->queueClient->sendMessages(WeclappConfig::WECLAPP_QUEUE, $queueSendMessageTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productTransfer
     *
     * @return \Generated\Shared\Transfer\CountryTransfer|null
     */
    protected function getCountryOfOrigin(ProductConcreteTransfer $productTransfer): ?CountryTransfer
    {
        $countryOfOriginName = null;

        foreach ($productTransfer->getLocalizedAttributes() as $localizedAttributes) {
            if ($localizedAttributes->getLocaleOrFail()->getLocaleNameOrFail() === WeclappConfig::LOCALE_CODE) {
                /** @var string $localizedAttributesAttributes */
                $localizedAttributesAttributes = $localizedAttributes->getAttributes();
                $countryOfOriginName = json_decode($localizedAttributesAttributes, true)['country_of_origin'] ?? null;
                break;
            }
        }

        if ($countryOfOriginName === null) {
            /** @var string $productTransferAttributes */
            $productTransferAttributes = $productTransfer->getAttributes();
            $countryOfOriginName = json_decode($productTransferAttributes, true)['country_of_origin'] ?? null;
        }

        if ($countryOfOriginName === null) {
            return null;
        }

        return $this->countryFacade->getCountryByName($countryOfOriginName);
    }
}
