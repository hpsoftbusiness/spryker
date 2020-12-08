<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\PostingExport\Business\ContentBuilder;

use DateTime;
use DateTimeZone;
use Generated\Shared\Transfer\ExportContentsTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\SalesOrderFilterTransfer;
use Pyz\Zed\PostingExport\PostingExportConfig;
use Pyz\Zed\Sales\Business\SalesFacadeInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Zed\Locale\Business\LocaleFacadeInterface;
use Spryker\Zed\Money\Business\MoneyFacadeInterface;

class PostingExportContentBuilder
{
    protected const FILENAME_FORMAT = "%s (%s)";
    protected const DATE_FORMAT_DMY = 'd.m.Y';
    protected const FILE_NAME_PREFIX = 'Posting Export';
    protected const FORMAT_ADDRESS = '%s, %s';

    protected const DEFAULT_DATA_INTERFACE_CODE = 'MP';
    protected const DEFAULT_DATA_COMPANY_CODE = 'MWS';
    protected const DEFAULT_DATA_AREA_TYPE = 'Verkauf';
    protected const DEFAULT_DATA_DOCUMENT_TYPE = 'Invoice/Credit Memo';
    protected const DEFAULT_DATA_ORDER_TYPE = 'MARKETP';
    protected const DEFAULT_DATA_CUSTOMER_POSTING_GROUP = 'MP';
    protected const DEFAULT_DATA_RETAIL_DOCUMENT = 'yes';
    protected const DEFAULT_DATA_PAYMENT_METHOD_CODE = 'MPCC_AD';

    protected const DEFAULT_LINE_DATA_TYPE = 'Artikel';
    protected const DEFAULT_LINE_DATA_UNITS_OF_MEASURE = 'PCS';

    /**
     * @var \Pyz\Zed\Sales\Business\SalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\Money\Business\MoneyFacadeInterface
     */
    protected $moneyFacade;

    /**
     * @var \Spryker\Zed\Locale\Business\LocaleFacadeInterface
     */
    protected $localeFacade;

    /**
     * @var \Spryker\Client\Store\StoreClientInterface
     */
    protected $storeClient;

    /**
     * @param \Pyz\Zed\Sales\Business\SalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\Money\Business\MoneyFacadeInterface $moneyFacade
     * @param \Spryker\Zed\Locale\Business\LocaleFacadeInterface $localeFacade
     * @param \Spryker\Client\Store\StoreClientInterface $storeClient
     */
    public function __construct(
        SalesFacadeInterface $salesFacade,
        MoneyFacadeInterface $moneyFacade,
        LocaleFacadeInterface $localeFacade,
        StoreClientInterface $storeClient
    ) {
        $this->salesFacade = $salesFacade;
        $this->moneyFacade = $moneyFacade;
        $this->localeFacade = $localeFacade;
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

        $localeTransfer = $this->localeFacade->getCurrentLocale();

        $postingExportContentsTransfer = new ExportContentsTransfer();
        $postingExportContentsTransfer->setFilename($this->getFileName($dateFrom, $dateTo));

        foreach ($orderIds as $idOrder) {
            $orderTransfer = $this->salesFacade->getOrderForExportByIdSalesOrder($idOrder);
            $postingExportContentsTransfer->addContentItem(
                $this->getPostingExportOrderData($orderTransfer, $localeTransfer)
            );
        }

        return $postingExportContentsTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return mixed[]
     */
    protected function getPostingExportOrderData(
        OrderTransfer $orderTransfer,
        LocaleTransfer $localeTransfer
    ): array {
        $customerTransfer = $orderTransfer->getCustomer();
        $billingAddressTransfer = $orderTransfer->getBillingAddress();
        $shippingAddressTransfer = $orderTransfer->getBillingAddress();
        $grandTotal = $orderTransfer->getTotals()->getGrandTotal();
        $taxTotal = $orderTransfer->getTotals()->getTaxTotal()->getAmount();
        $excludingVatTotal = $grandTotal - $taxTotal;
        $postingDate = $orderTransfer->getInvoiceCreatedAt()
            ? $this->getFormattedDate($orderTransfer->getInvoiceCreatedAt())
            : null;

        $adyenPaymentReference = $this->findAdyenPaymentReference($orderTransfer);
        $orderInvoiceReference = $this->findOrderInvoiceReference($orderTransfer);

        $orderItemsCount = 0;
        $orderItemsData = [];
        foreach ($orderTransfer->getItems() as $itemTransfer) {
            $orderItemsCount += $itemTransfer->getQuantity();
            $orderItemsData[] = $this->getPostingExportOrderItemData(
                $itemTransfer,
                $localeTransfer,
                $orderItemsCount + 1,
                $orderInvoiceReference
            );
        }

        return [
            'interfaceCode' => static::DEFAULT_DATA_INTERFACE_CODE,
            'companyCode' => static::DEFAULT_DATA_COMPANY_CODE,
            'refDocumentNumber' => $orderInvoiceReference,
            'docArchiveFileReference' => null, // skipped
            'areaType' => static::DEFAULT_DATA_AREA_TYPE,
            'documentType' => static::DEFAULT_DATA_DOCUMENT_TYPE,
            'orderType' => static::DEFAULT_DATA_ORDER_TYPE,
            'orderDate' => $this->getFormattedDate($orderTransfer->getCreatedAt()),
            'postingDate' => $postingDate,
            'documentDate' => $postingDate,
            'orderNumber' => $orderTransfer->getOrderReference(),
            'billToCustomerNumber' => $customerTransfer->getMyWorldCustomerNumber(),
            'customerType' => $customerTransfer->getCustomerType(),
            'vatBusPostingGroup' => null, // TODO
            'customerPostingGroup' => static::DEFAULT_DATA_CUSTOMER_POSTING_GROUP,
            'genBusinessPostingGroup' => null, // TODO
            'billToName' => sprintf(
                '%s %s',
                $billingAddressTransfer->getFirstName(),
                $billingAddressTransfer->getLastName()
            ),
            'billToCountry' => $billingAddressTransfer->getCountry()->getName(),
            'billToAddress' => sprintf(
                static::FORMAT_ADDRESS,
                $billingAddressTransfer->getAddress1(),
                $billingAddressTransfer->getAddress2()
            ),
            'billToCity' => $billingAddressTransfer->getCity(),
            'billToPostCode' => $billingAddressTransfer->getZipCode(),
            'shipToCountry' => $shippingAddressTransfer->getCountry()->getName(),
            'shipToAddress' => sprintf(
                static::FORMAT_ADDRESS,
                $shippingAddressTransfer->getAddress1(),
                $shippingAddressTransfer->getAddress2()
            ),
            'shipToCity' => $shippingAddressTransfer->getCity(),
            'shipToPostCode' => $shippingAddressTransfer->getZipCode(),
            'vatRegistrationNumber' => $billingAddressTransfer->getVatNumber(),
            'retailDocument' => static::DEFAULT_DATA_RETAIL_DOCUMENT,
            'amount' => $this->formatIntValueToDecimalCurrency($excludingVatTotal),
            'amountIncludingVat' => $this->formatIntValueToDecimalCurrency($grandTotal),
            'vatAmount' => $this->formatIntValueToDecimalCurrency($taxTotal),
            'currencyCode' => $orderTransfer->getCurrencyIsoCode(),
            'currencyFactor' => null, // skipped
            'paymentMethodCode' => static::DEFAULT_DATA_PAYMENT_METHOD_CODE,
            'discount' => $this->formatIntValueToDecimalCurrency($orderTransfer->getTotals()->getDiscountTotal()),
            'paymentReferenceId' => $adyenPaymentReference,
            'cashBackNumber' => $customerTransfer->getMyWorldCustomerNumber(),
            'noOfLines' => $orderItemsCount,
            'lines' => $orderItemsData,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param int $indexNumber
     * @param string|null $orderInvoiceReference
     *
     * @return mixed[]
     */
    protected function getPostingExportOrderItemData(
        ItemTransfer $itemTransfer,
        LocaleTransfer $localeTransfer,
        int $indexNumber,
        ?string $orderInvoiceReference
    ): array {
        $discountTotal = $itemTransfer->getSumDiscountAmountFullAggregation();
        $discountPercentage = (int)(round($discountTotal / $itemTransfer->getSumPrice(), 2) * 100);
        $grandTotal = $itemTransfer->getSumPrice() - $discountTotal;
        $taxTotal = $itemTransfer->getSumTaxAmount();
        $excludingVatTotal = $grandTotal - $taxTotal;

        $productDescription = $this->findProductDescription(
            $itemTransfer->getProductConcrete(),
            $localeTransfer
        );

        return [
            'interfaceCode' => static::DEFAULT_DATA_INTERFACE_CODE,
            'companyCode' => static::DEFAULT_DATA_COMPANY_CODE,
            'refDocumentNo' => $orderInvoiceReference,
            'refInvoiceDocumentNo' => $orderInvoiceReference,
            'docArchiveFileReference' => null, // skipped
            'lineNo' => $indexNumber,
            'type' => static::DEFAULT_LINE_DATA_TYPE,
            'no' => $itemTransfer->getProductConcrete()->getSku(),
            'description' => $productDescription,
            'itemCategory' => null, // check
            'unitOfMeasure' => static::DEFAULT_LINE_DATA_UNITS_OF_MEASURE,
            'quantity' => $itemTransfer->getQuantity(),
            'genProdPostingGroup' => 'I_MP_NO', // check
            'vatProdPostingGroup' => 'T_NO', // check
            'glAccount' => null, // skipped
            'vatPercentage' => (int)$itemTransfer->getTaxRate(),
            'unitPrice' => $this->formatIntValueToDecimalCurrency($itemTransfer->getUnitPrice()),
            'amount' => $this->formatIntValueToDecimalCurrency($excludingVatTotal),
            'amountIncludingVat' => $this->formatIntValueToDecimalCurrency($grandTotal),
            'vatAmount' => $this->formatIntValueToDecimalCurrency($taxTotal),
            'discountAmount' => $this->formatIntValueToDecimalCurrency($discountTotal),
            'discountPercentage' => $discountPercentage,
            'Intrastat' => null, // check
            'unitCost' => null, // check
            'costAmount' => null, // check
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string|null
     */
    protected function findAdyenPaymentReference(OrderTransfer $orderTransfer): ?string
    {
        if (!$orderTransfer->getAdyenPayment()) {
            return null;
        }

        return $orderTransfer->getAdyenPayment()->getReference();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return string|null
     */
    protected function findProductDescription(
        ProductConcreteTransfer $productConcreteTransfer,
        LocaleTransfer $localeTransfer
    ): ?string {
        if (!$productConcreteTransfer->getLocalizedAttributes()->count()) {
            return null;
        }

        foreach ($productConcreteTransfer->getLocalizedAttributes() as $localizedAttributesTransfer) {
            if ($localizedAttributesTransfer->getLocale()->getLocaleName() === $localeTransfer->getLocaleName()) {
                return $localizedAttributesTransfer->getDescription();
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\OrderTransfer $orderTransfer
     *
     * @return string|null
     */
    protected function findOrderInvoiceReference(OrderTransfer $orderTransfer): ?string
    {
        if (!$orderTransfer->getOrderInvoices()->count()) {
            return null;
        }

        return $orderTransfer->getOrderInvoices()[0]->getReference();
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
