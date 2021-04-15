<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Zed\MyWorldPayment\Business\Calculator;

use ArrayObject;
use Generated\Shared\Transfer\CalculableObjectTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface;
use Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig;
use Spryker\Client\Locale\LocaleClientInterface;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;

class BenefitVoucherPaymentCalculator implements MyWorldPaymentCalculatorInterface
{
    protected const DEFAULT_AMOUNT_OF_ITEMS = 1;

    /**
     * @var \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface
     */
    protected $marketplaceApiClient;

    /**
     * @var \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig
     */
    protected $myWorldPaymentConfig;

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    private $productStorageClient;

    /**
     * @var \Spryker\Client\Locale\LocaleClientInterface
     */
    private $localeClient;

    /**
     * @param \Pyz\Client\MyWorldMarketplaceApi\MyWorldMarketplaceApiClientInterface $marketplaceApiClient
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     * @param \Spryker\Client\Locale\LocaleClientInterface $localeClient
     * @param \Pyz\Zed\MyWorldPayment\MyWorldPaymentConfig $myWorldPaymentConfig
     */
    public function __construct(
        MyWorldMarketplaceApiClientInterface $marketplaceApiClient,
        ProductStorageClientInterface $productStorageClient,
        LocaleClientInterface $localeClient,
        MyWorldPaymentConfig $myWorldPaymentConfig
    ) {
        $this->marketplaceApiClient = $marketplaceApiClient;
        $this->myWorldPaymentConfig = $myWorldPaymentConfig;
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateQuote(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $calculableObjectTransfer = $this->removePaymentMethod($calculableObjectTransfer);

        if ($this->isBenefitVoucherUseSelected($calculableObjectTransfer)) {
            $calculableObjectTransfer = $this->reduceItemsPrices($calculableObjectTransfer);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    public function recalculateOrder(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function reduceItemsPrices(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $commonReduceAmount = 0;

        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher() && $this->isDiscountApplicableForProduct($itemTransfer)) {
                $newPriceForItemsWithBenefitVouchers = $this->getSalesPrice($itemTransfer) * $this->getAmountOfItemsThatUseBenefitVouchers($itemTransfer);
                $oldPriceForItemsWithBenefitVouchers = ($itemTransfer->getUnitPrice()) * $this->getAmountOfItemsThatUseBenefitVouchers($itemTransfer);

                $commonReduceAmount += ($oldPriceForItemsWithBenefitVouchers - $newPriceForItemsWithBenefitVouchers);
            }
        }

        if ($commonReduceAmount > 0) {
            $payment = $this->createPaymentMethod($commonReduceAmount);

            $calculableObjectTransfer->addPayment($payment);
        }

        return $calculableObjectTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getAmountOfItemsThatUseBenefitVouchers(ItemTransfer $itemTransfer): int
    {
        return $itemTransfer->getAmountItemsToUseBenefitVoucher() ?? static::DEFAULT_AMOUNT_OF_ITEMS;
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return \Generated\Shared\Transfer\CalculableObjectTransfer
     */
    protected function removePaymentMethod(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        $newList = new ArrayObject();

        foreach ($calculableObjectTransfer->getPayments() as $payment) {
            if ($payment->getPaymentSelection() !== $this->myWorldPaymentConfig->getOptionBenefitVoucherName()) {
                $newList->append($payment);
            }
        }

        $calculableObjectTransfer->setPayments($newList);

        return $calculableObjectTransfer;
    }

    /**
     * @param int $amountOfCharged
     *
     * @return \Generated\Shared\Transfer\PaymentTransfer
     */
    protected function createPaymentMethod(int $amountOfCharged): PaymentTransfer
    {
        return (new PaymentTransfer())
            ->setAmount($amountOfCharged)
            ->setPaymentProvider($this->myWorldPaymentConfig->getOptionBenefitVoucherName())
            ->setPaymentMethodName($this->myWorldPaymentConfig->getOptionBenefitVoucherName())
            ->setPaymentMethod($this->myWorldPaymentConfig->getOptionBenefitVoucherName())
            ->setPaymentSelection($this->myWorldPaymentConfig->getOptionBenefitVoucherName());
    }

    /**
     * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
     *
     * @return bool
     */
    protected function isBenefitVoucherUseSelected(CalculableObjectTransfer $calculableObjectTransfer): bool
    {
        foreach ($calculableObjectTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isDiscountApplicableForProduct(ItemTransfer $itemTransfer): bool
    {
        $product = $this->getAbstractProductForItem($itemTransfer);
        $attributes = $itemTransfer->getConcreteAttributes();

        if ($product) {
            $attributes = $product['attributes'];
        }

        return isset($attributes[$this->myWorldPaymentConfig->getProductAttributeKeyBenefitStore()])
            ? $attributes[$this->myWorldPaymentConfig->getProductAttributeKeyBenefitStore()]
            : false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return int
     */
    protected function getSalesPrice(ItemTransfer $itemTransfer): int
    {
        $product = $this->getAbstractProductForItem($itemTransfer);
        $attributes = $itemTransfer->getConcreteAttributes();

        if ($product) {
            $attributes = $product['attributes'];
        }

        return $attributes[$this->myWorldPaymentConfig->getProductAttributeKeyBenefitSalesPrice()] * 100;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array|null
     */
    protected function getAbstractProductForItem(ItemTransfer $itemTransfer): ?array
    {
        $locale = $this->localeClient->getCurrentLocale();

        return $this->productStorageClient->findProductAbstractStorageData($itemTransfer->getIdProductAbstract(), $locale);
    }
}
