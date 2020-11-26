<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport\Business\ContentBuilder;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\ExportContentsTransfer;
use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Pyz\Zed\PostingExport\PostingExportConfig;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;
use Spryker\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;

class PostingExportContentBuilder
{
    protected const FILENAME_FORMAT = "%s (%s)";
    protected const DATE_FORMAT_DMY = 'd.m.Y';
    protected const FILE_NAME_PREFIX = 'Posting Export';

    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    protected $storeClient;

    /**
     * @var \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @param \Spryker\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Money\Business\MoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\Translator\Business\TranslatorFacadeInterface $translatorFacade
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct(
        SalesFacadeInterface $salesFacade,
        MoneyFacadeInterface $moneyFacade,
        TranslatorFacadeInterface $translatorFacade,
        StoreClientInterface $storeClient
    ) {
        $this->salesFacade = $salesFacade;
        $this->moneyFacade = $moneyFacade;
        $this->translatorFacade = $translatorFacade;
        $this->storeClient = $storeClient;
    }

    /**
     * @param \DateTime|null $dateFrom
     * @param \DateTime|null $dateTo
     *
     * @return \Generated\Shared\Transfer\ExportContentsTransfer|null
     */
    public function generateContent(?DateTime $dateFrom, ?DateTime $dateTo): ?ExportContentsTransfer
    {
        $salesOrderFilterTransfer = $this->getSalesOrderFilter($dateFrom, $dateTo);
        $orderTransfers = $this->salesFacade->getOrdersBySalesOrderFilter($salesOrderFilterTransfer);

        if (empty($orderTransfers)) {
            return null;
        }

        $postingExportContentsTransfer = new ExportContentsTransfer();
        $postingExportContentsTransfer->setFilename($this->getFileName($dateFrom, $dateTo));
        $postingExportContentsTransfer->addContentItem($this->getExportOrderHeaders());

        foreach ($orderTransfers as $orderTransfer) {
                $postingExportContentsTransfer->addContentItem(
                    [
                        $orderTransfer->getIdSalesOrder(),
                        $orderTransfer->getOrderReference(),
                        $orderTransfer->getCustomerReference(),
                        $this->getFormattedDate($orderTransfer->getCreatedAt()),
                        $this->formatIntValueToDecimalCurrency($orderTransfer->getTotals()->getGrandTotal()),
                        $this->formatIntValueToDecimalCurrency($orderTransfer->getTotals()->getTaxTotal()->getAmount()),
                        $this->formatIntValueToDecimalCurrency($orderTransfer->getTotals()->getExpenseTotal()),
                    ]
                );
        }

        return $postingExportContentsTransfer;
    }

    /**
     * @return string
     */
    protected function getFileNamePrefix(): string
    {
        return static::FILE_NAME_PREFIX;
    }

    /**
     * @return array
     */
    protected function getExportOrderHeaders(): array
    {
        return [
            $this->translatorFacade->trans('export.posting.order-id'),
            $this->translatorFacade->trans('export.posting.order-reference'),
            $this->translatorFacade->trans('export.posting.customer-reference'),
            $this->translatorFacade->trans('export.posting.date'),
            $this->translatorFacade->trans('export.posting.grand-total'),
            $this->translatorFacade->trans('export.posting.tax-total'),
            $this->translatorFacade->trans('export.posting.expense-total'),
        ];
    }

    /**
     * @param \DateTime|null $invoiceCreationDateFrom
     * @param \DateTime|null $invoiceCreationDateTo
     *
     * @return \Generated\Shared\Transfer\SalesOrderFilterTransfer
     */
    protected function getSalesOrderFilter(
        ?DateTime $invoiceCreationDateFrom,
        ?DateTime $invoiceCreationDateTo
    ): SalesOrderFilterTransfer {
        $salesOrderFilterTransfer = new SalesOrderFilterTransfer();
        if ($invoiceCreationDateFrom) {
            $invoiceCreationDateFrom->setTime(0, 0, 0);
            $salesOrderFilterTransfer->setCreatedFrom(
                $invoiceCreationDateFrom->format('c')
            );
        }

        if ($invoiceCreationDateTo) {
            $invoiceCreationDateTo->setTime(23, 59, 59);
            $salesOrderFilterTransfer->setCreatedTo(
                $invoiceCreationDateTo->format('c')
            );
        }

        return $salesOrderFilterTransfer;
    }

    /**
     * @param \DateTime|null $dateFrom
     * @param \DateTime|null $dateTo
     *
     * @return string
     */
    protected function getFileName(?DateTime $dateFrom, ?DateTime $dateTo): string
    {
        $filenameFragments = [];

        if ($dateFrom) {
            $filenameFragments[] = $dateFrom->format(PostingExportConfig::DATE_FORMAT);
        }

        if ($dateTo) {
            $filenameFragments[] = $dateTo->format(PostingExportConfig::DATE_FORMAT);
        }

        if (empty($filenameFragments)) {
            $dateTimeZone = new DateTimeZone($this->storeClient->getCurrentStore()->getTimezone());
            $filenameFragments[] = (new DateTime())
                ->setTimezone($dateTimeZone)
                ->format(PostingExportConfig::DATE_FORMAT);
        }

        return sprintf(
            static::FILENAME_FORMAT,
            $this->getFileNamePrefix(),
            implode(PostingExportConfig::FILE_NAME_DELIMITER, array_unique($filenameFragments))
        );
    }

    /**
     * @param int $value
     *
     * @return string
     */
    protected function formatIntValueToDecimalCurrency(int $value): string
    {
        $moneyTransfer = $this->moneyFacade->fromInteger($value);

        return $this->moneyFacade->formatWithoutSymbol($moneyTransfer);
    }

    /**
     * @param string $date
     *
     * @return string
     */
    protected function getFormattedDate(string $date): string
    {
        $timeZone = new DateTimeZone($this->storeClient->getCurrentStore()->getTimezone());

        return (new DateTime($date, $timeZone))->format(static::DATE_FORMAT_DMY);
    }
}
