<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\QueueErrorChecker\Communication\Console;

use Exception;
use Spryker\Shared\AvailabilityStorage\AvailabilityStorageConstants;
use Spryker\Shared\CategoryPageSearch\CategoryPageSearchConstants;
use Spryker\Shared\CategoryStorage\CategoryStorageConstants;
use Spryker\Shared\CmsPageSearch\CmsPageSearchConstants;
use Spryker\Shared\CmsStorage\CmsStorageConstants;
use Spryker\Shared\ConfigurableBundlePageSearch\ConfigurableBundlePageSearchConfig;
use Spryker\Shared\ConfigurableBundleStorage\ConfigurableBundleStorageConfig;
use Spryker\Shared\ContentStorage\ContentStorageConfig;
use Spryker\Shared\CustomerAccessStorage\CustomerAccessStorageConstants;
use Spryker\Shared\Event\EventConstants;
use Spryker\Shared\FileManagerStorage\FileManagerStorageConstants;
use Spryker\Shared\GlossaryStorage\GlossaryStorageConfig;
use Spryker\Shared\MerchantProductOfferStorage\MerchantProductOfferStorageConfig;
use Spryker\Shared\MerchantStorage\MerchantStorageConfig;
use Spryker\Shared\PriceProductOfferStorage\PriceProductOfferStorageConfig;
use Spryker\Shared\PriceProductStorage\PriceProductStorageConstants;
use Spryker\Shared\ProductReviewSearch\ProductReviewSearchConfig;
use Spryker\Shared\ProductStorage\ProductStorageConstants;
use Spryker\Shared\Publisher\PublisherConfig;
use Spryker\Shared\SalesReturnSearch\SalesReturnSearchConfig;
use Spryker\Shared\TaxProductStorage\TaxProductStorageConfig;
use Spryker\Shared\TaxStorage\TaxStorageConfig;
use Spryker\Shared\UrlStorage\UrlStorageConstants;
use Spryker\Zed\Kernel\Communication\Console\Console;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Pyz\Zed\QueueErrorChecker\Business\QueueErrorCheckerFacade getFacade()
 * @method \Pyz\Zed\QueueErrorChecker\Communication\QueueErrorCheckerCommunicationFactory getFactory()
 */
class QueueErrorCheckerConsole extends Console
{
    protected const OPTION_LIMIT = 'limit';
    protected const OPTION_LIMIT_SHORT = 'l';
    protected const OPTION_LIMIT_DEFAULT = 1;
    protected const OPTION_LIMIT_DESCRIPTION = 'Defines what amount of events must be read from the queue';
    protected const NO_ERRORS_MESSAGE = '<info>No errors in Queue: "%s"</info>';

    public const COMMAND_NAME = 'queue:check:errors';
    public const DESCRIPTION = 'Checking RabbitMQ for errors';

    private const ERROR_QUEUES = [
        EventConstants::EVENT_QUEUE_ERROR,
        'log-queue.error',
        PublisherConfig::PUBLISH_ERROR_QUEUE,
        'publish.translation.error',
        CategoryPageSearchConstants::CATEGORY_SYNC_SEARCH_ERROR_QUEUE,
        CmsPageSearchConstants::CMS_SYNC_SEARCH_ERROR_QUEUE,
        ConfigurableBundlePageSearchConfig::CONFIGURABLE_BUNDLE_SEARCH_ERROR_QUEUE,
        'sync.search.merchant.error',
        ProductReviewSearchConfig::PRODUCT_REVIEW_SYNC_SEARCH_ERROR_QUEUE,
        SalesReturnSearchConfig::SYNC_SEARCH_RETURN_ERROR,
        AvailabilityStorageConstants::AVAILABILITY_SYNC_STORAGE_ERROR_QUEUE,
        CategoryStorageConstants::CATEGORY_SYNC_STORAGE_ERROR_QUEUE,
        CmsStorageConstants::CMS_SYNC_STORAGE_ERROR_QUEUE,
        ConfigurableBundleStorageConfig::CONFIGURABLE_BUNDLE_SYNC_STORAGE_ERROR_QUEUE,
        ContentStorageConfig::CONTENT_SYNC_STORAGE_ERROR_QUEUE,
        CustomerAccessStorageConstants::CUSTOMER_ACCESS_SYNC_STORAGE_ERROR_QUEUE,
        FileManagerStorageConstants::FILE_SYNC_STORAGE_ERROR_QUEUE,
        MerchantStorageConfig::MERCHANT_SYNC_STORAGE_ERROR_QUEUE,
        MerchantProductOfferStorageConfig::MERCHANT_PRODUCT_OFFER_SYNC_STORAGE_ERROR_QUEUE,
        'sync.storage.merchant_product_offer.error.error',
        PriceProductStorageConstants::PRICE_SYNC_STORAGE_ERROR_QUEUE,
        PriceProductOfferStorageConfig::PRICE_PRODUCT_OFFER_OFFER_SYNC_STORAGE_ERROR_QUEUE,
        ProductStorageConstants::PRODUCT_SYNC_STORAGE_ERROR_QUEUE,
        TaxProductStorageConfig::PRODUCT_ABSTRACT_TAX_SET_SYNC_STORAGE_ERROR_QUEUE,
        TaxStorageConfig::TAX_SET_SYNC_STORAGE_ERROR_QUEUE,
        GlossaryStorageConfig::SYNC_STORAGE_TRANSLATION_ERROR,
        UrlStorageConstants::URL_SYNC_STORAGE_ERROR_QUEUE,
    ];

    /**
     * @return void
     */
    protected function configure()
    {
        $this->setName(static::COMMAND_NAME)
            ->setDescription(static::DESCRIPTION);

        $this->addOption(
            static::OPTION_LIMIT,
            static::OPTION_LIMIT_SHORT,
            InputOption::VALUE_OPTIONAL,
            static::OPTION_LIMIT_DESCRIPTION,
            static::OPTION_LIMIT_DEFAULT
        );
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $limit = (int)$input->getOption(static::OPTION_LIMIT);
        $hasErrors = false;
        foreach (self::ERROR_QUEUES as $queueName) {
            try {
                $messages = $this->getFacade()->getQueueErrorMessages($queueName, $limit);

                if (empty($messages)) {
                    $output->writeln(sprintf(static::NO_ERRORS_MESSAGE, $queueName));
                    continue;
                }
                foreach ($messages as $message) {
                    $output->writeln(sprintf("<error>%s</error>", $message));
                }
                $hasErrors = true;
            } catch (Exception $exception) {
                $output->writeln(sprintf("<comment>%s</comment>", $exception->getMessage()));
            }
        }

        return ($hasErrors) ? static::CODE_ERROR : static::CODE_SUCCESS;
    }
}
