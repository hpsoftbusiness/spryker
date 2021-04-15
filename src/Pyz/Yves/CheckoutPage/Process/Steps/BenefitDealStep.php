<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Process\Steps;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Pyz\Yves\CheckoutPage\CheckoutPageConfig;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Step\StepWithBreadcrumbInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface;
use SprykerShop\Yves\CheckoutPage\Process\Steps\AbstractBaseStep;

class BenefitDealStep extends AbstractBaseStep implements StepWithBreadcrumbInterface
{
    protected const STEP_TITLE = 'checkout.step.benefit_deal.title';

    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    private $productStorageClient;

    /**
     * @var \Spryker\Client\Locale\LocaleClient
     */

    private $localeClient;

    /**
     * @var \Pyz\Yves\CheckoutPage\CheckoutPageConfig
     */
    private $checkoutPageConfig;

    /**
     * @param \Spryker\Client\ProductStorage\ProductStorageClientInterface $productStorageClient
     * @param \SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface $localeClient
     * @param \Pyz\Yves\CheckoutPage\CheckoutPageConfig $checkoutPageConfig
     * @param string $stepRoute
     * @param string|null $escapeRoute
     */
    public function __construct(
        ProductStorageClientInterface $productStorageClient,
        CheckoutPageToLocaleClientInterface $localeClient,
        CheckoutPageConfig $checkoutPageConfig,
        string $stepRoute,
        ?string $escapeRoute
    ) {
        parent::__construct($stepRoute, $escapeRoute);
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
        $this->checkoutPageConfig = $checkoutPageConfig;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function requireInput(AbstractTransfer $dataTransfer): bool
    {
        return $this->assertCartHasApplicableBenefitDeals($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function postCondition(AbstractTransfer $dataTransfer): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getBreadcrumbItemTitle(): string
    {
        return static::STEP_TITLE;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemEnabled(AbstractTransfer $dataTransfer): bool
    {
        /**
         * @var \Generated\Shared\Transfer\QuoteTransfer $dataTransfer
         */
        return $this->hasBenefitDealsApplied($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return bool
     */
    public function isBreadcrumbItemHidden(AbstractTransfer $dataTransfer): bool
    {
        return !$this->requireInput($dataTransfer);
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $dataTransfer
     *
     * @return array
     */
    public function getTemplateVariables(AbstractTransfer $dataTransfer): array
    {
        $availableBalance = $dataTransfer->getCustomer()->getCustomerBalance();

        $products = array_reduce(
            $dataTransfer->getItems()->getArrayCopy(),
            function (ArrayObject $carry, ItemTransfer $itemTransfer) {
                $product = $this->getProductFromStorageForItemTransfer($itemTransfer);
                $attributes = $product ? $product['attributes'] : $itemTransfer->getConcreteAttributes();
                $currencyCode = $itemTransfer->getPriceProduct()->getMoneyValue()->getCurrency()->getCode();

                /**
                 * @TODO refactor properly after all payment branches are merged.
                 */
                if ($product && ($this->isBenefitVouchersDataProvidedForProductAttributes($attributes))) {
                    $carry[$itemTransfer->getIdProductAbstract()] = [
                        'prices' => $this->calculateSalesPriceForQuantity($itemTransfer, $attributes),
                        'currencyCode' => $currencyCode,
                        'unitPrice' => number_format((float)$itemTransfer->getUnitPrice() / 100, 2),
                    ];
                } elseif ($product && $itemTransfer->getShoppingPointsDeal() && $itemTransfer->getShoppingPointsDeal()->getIsActive()) {
                    $carry[$itemTransfer->getIdProductAbstract()] = [
                        'prices' => [],
                        'currencyCode' => $currencyCode,
                        'unitPrice' => $itemTransfer->getOriginUnitGrossPrice(),
                    ];
                }

                return $carry;
            },
            new ArrayObject()
        );

        return [
            'benefitSalesInfo' => $products,
            'customerBalance' => [
                'benefitVouchersBalance' => $availableBalance->getAvailableBenefitVoucherAmount()->toFloat(),
                'benefitVouchersCurrencyCode' => $availableBalance->getAvailableBenefitVoucherCurrency(),
                'cashbackBalance' => $availableBalance->getAvailableCashbackAmount()->toFloat(),
                'cashbackCurrencyCode' => $availableBalance->getAvailableCashbackCurrency(),
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $productAttributes
     *
     * @return array
     */
    protected function calculateSalesPriceForQuantity(ItemTransfer $itemTransfer, array $productAttributes): array
    {
        return array_reduce(
            range(1, (int)$itemTransfer->getQuantity()),
            function (array $carry, $index) use ($itemTransfer, $productAttributes) {
                $salesPrice = ((float)$productAttributes[$this->checkoutPageConfig->getBenefitSalesPriceKey()]) * $index;
                $benefitAmount = $productAttributes[$this->checkoutPageConfig->getBenefitAmountKey()] * $index;

                $carry[] = [
                    'salesPrice' => $salesPrice,
                    'benefitAmount' => $benefitAmount,
                    'quantity' => $index,
                ];

                return $carry;
            },
            []
        );
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function assertCartHasApplicableBenefitDeals(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getShoppingPointsDeal() && $itemTransfer->getShoppingPointsDeal()->getIsActive()) {
                return true;
            }

            $product = $this->getProductFromStorageForItemTransfer($itemTransfer);
            $attributes = $product ? $product['attributes'] : null;

            if ($attributes && $this->isBenefitVouchersDataProvidedForProductAttributes($attributes)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $attributes
     *
     * @return bool
     */
    protected function isBenefitVouchersDataProvidedForProductAttributes(array $attributes): bool
    {
        $benefitStoreKey = $this->checkoutPageConfig->getBenefitStoreKey();
        $benefitSalesPriceKey = $this->checkoutPageConfig->getBenefitSalesPriceKey();
        $benefitAmountKey = $this->checkoutPageConfig->getBenefitAmountKey();

        return isset($attributes[$benefitAmountKey])
            && isset($benefitSalesPriceKey)
            && isset($attributes[$benefitStoreKey]);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function hasBenefitDealsApplied(QuoteTransfer $quoteTransfer): bool
    {
        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getUseBenefitVoucher() || $itemTransfer->getUseShoppingPoints()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array|null
     */
    protected function getProductFromStorageForItemTransfer(ItemTransfer $itemTransfer): ?array
    {
        return $this->productStorageClient->findProductAbstractStorageData(
            $itemTransfer->getIdProductAbstract(),
            $this->localeClient->getCurrentLocale()
        );
    }
}
