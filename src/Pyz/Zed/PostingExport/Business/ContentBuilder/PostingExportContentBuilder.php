<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport\Business\ContentBuilder;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\ExportContentsTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Pyz\Zed\PostingExport\PostingExportConfig;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;
use Spryker\Zed\Translator\Business\TranslatorFacadeInterface;

class PostingExportContentBuilder
{
    protected const FILENAME_FORMAT = "%s (%s)";
    protected const DATE_FORMAT_DMY = 'd.m.Y';
    protected const FILE_NAME_PREFIX = 'Posting Export';

    protected const DEFAULT_DATA_INTERFACE_CODE = 'MP';
    protected const DEFAULT_DATA_COMPANY_CODE = 'MWS';
    protected const DEFAULT_DATA_AREA_TYPE = 'Verkauf';
    protected const DEFAULT_DATA_DOCUMENT_TYPE = 'Invoice/Credit Memo';

    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\Translator\Business\TranslatorFacadeInterface
     */
    protected $translatorFacade;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
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
        $orderIds = $this->salesFacade->getOrderIdsBySalesOrderFilter($salesOrderFilterTransfer);

        if (!$orderIds) {
            return null;
        }

        $postingExportContentsTransfer = new ExportContentsTransfer();
        $postingExportContentsTransfer->setFilename($this->getFileName($dateFrom, $dateTo));

        foreach ($orderIds as $idOrder) {
            $orderTransfer = $this->salesFacade->getOrderByIdSalesOrder($idOrder);
            $postingExportContentsTransfer->addContentItem(
                $this->getPostingExportContentItemData($orderTransfer)
            );
        }

        return $postingExportContentsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return mixed[]
     */
    protected function getPostingExportContentItemData(OrderTransfer $orderTransfer): array
    {
        $customerTransfer = $orderTransfer->getCustomer();
        $billingAddressTransfer = $orderTransfer->getBillingAddress();
        $shippingAddressTransfer = $orderTransfer->getBillingAddress();
        $amountExcludingVat = $orderTransfer->getTotals()->getGrandTotal() - $orderTransfer->getTotals()->getTaxTotal()->getAmount();
        $postingDate = $orderTransfer->getInvoiceCreatedAt()
            ? $this->getFormattedDate($orderTransfer->getInvoiceCreatedAt())
            : null;

        $orderItemsCount = 0;
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderItemsCount += $itemTransfer->getQuantity();
        }

        return [
            'interfaceCode' => static::DEFAULT_DATA_INTERFACE_CODE,
            'companyCode' => static::DEFAULT_DATA_COMPANY_CODE,
            'refDocumentNumber' => null, // TODO
            'docArchiveFileReference' => null, // TODO
            'areaType' => static::DEFAULT_DATA_AREA_TYPE,
            'documentType' => static::DEFAULT_DATA_DOCUMENT_TYPE,
            'orderType' => null, // TODO
            'orderDate' => $this->getFormattedDate($orderTransfer->getCreatedAt()),
            'postingDate' => $postingDate,
            'documentDate' => $postingDate,
            'orderNumber' => null, // TODO
            'billToCustomerNumber' => $customerTransfer->getMyWorldCustomerNumber(),
            'customerType' => $customerTransfer->getCustomerType(),
            'vatBusPostingGroup' => null, // TODO
            'customerPostingGroup' => null, // TODO
            'genBusinessPostingGroup' => null, // TODO
            'billToName' => sprintf('%s %s', $billingAddressTransfer->getFirstName(), $billingAddressTransfer->getLastName()),
            'billToCountry' => $billingAddressTransfer->getCountry(),
            'billToAddress' => sprintf('%s, %s', $billingAddressTransfer->getAddress1(), $billingAddressTransfer->getAddress2()),
            'billToCity' => $billingAddressTransfer->getCity(),
            'billToPostCode' => $billingAddressTransfer->getZipCode(),
            'shipToCountry' => $shippingAddressTransfer->getCountry(),
            'shipToAddress' => sprintf('%s, %s', $shippingAddressTransfer->getAddress1(), $shippingAddressTransfer->getAddress2()),
            'shipToCity' => $shippingAddressTransfer->getCity(),
            'shipToPostCode' => $shippingAddressTransfer->getZipCode(),
            'vatRegistrationNumber' => $billingAddressTransfer->getVatNumber(),
            'retailDocument' => 'yes',
            'amount' => $this->formatIntValueToDecimalCurrency($amountExcludingVat),
            'amountIncludingVat' => $this->formatIntValueToDecimalCurrency($orderTransfer->getTotals()->getGrandTotal()),
            'vatAmount' => $this->formatIntValueToDecimalCurrency($orderTransfer->getTotals()->getTaxTotal()->getAmount()),
            'currencyCode' => $orderTransfer->getCurrencyIsoCode(),
            'currencyFactor' => null, // skipped
            'paymentMethodCode' => null, // TODO
            'discount' => null, // TODO
            'paymentReferenceId' => null, // TODO
            'cashBackNumber' => $customerTransfer->getMyWorldCustomerNumber(), // TODO
            'noOfLines' => $orderItemsCount,
        ];
    }

    /**
     * @return string
     */
    protected function getFileNamePrefix(): string
    {
        return static::FILE_NAME_PREFIX;
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
