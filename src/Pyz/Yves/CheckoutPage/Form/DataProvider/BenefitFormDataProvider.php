<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\DataProvider;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Pyz\Yves\CheckoutPage\CheckoutPageConfig;
use Pyz\Yves\CheckoutPage\Form\Steps\BenefitVoucher\BenefitVoucherCollectionForm;
use Spryker\Client\ProductStorage\ProductStorageClientInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Yves\StepEngine\Dependency\Form\StepEngineFormDataProviderInterface;
use SprykerShop\Yves\CheckoutPage\Dependency\Client\CheckoutPageToLocaleClientInterface;

class BenefitFormDataProvider implements StepEngineFormDataProviderInterface
{
    /**
     * @var \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    private $productStorageClient;

    /**
     * @var \Spryker\Client\Locale\LocaleClientInterface
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
     */
    public function __construct(
        ProductStorageClientInterface $productStorageClient,
        CheckoutPageToLocaleClientInterface $localeClient,
        CheckoutPageConfig $checkoutPageConfig
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->localeClient = $localeClient;
        $this->checkoutPageConfig = $checkoutPageConfig;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getData(AbstractTransfer $quoteTransfer)
    {
        return $quoteTransfer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $quoteTransfer
     *
     * @return array
     */
    public function getOptions(AbstractTransfer $quoteTransfer)
    {
        return [
            BenefitVoucherCollectionForm::OPTION_KEY_ITEMS => array_reduce(
                $quoteTransfer->getItems()->getArrayCopy(),
                function (ArrayObject $carry, ItemTransfer $itemTransfer) {
                    $attributes = $this->getProductAttributes($itemTransfer);

                    if ($this->isAttributesProvidedForBenefitVoucher($attributes)) {
                        $carry->append($itemTransfer);
                    }

                    return $carry;
                },
                new ArrayObject()
            ),
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array
     */
    protected function getProductAttributes(ItemTransfer $itemTransfer): array
    {
        $product = $this->productStorageClient->findProductAbstractStorageData(
            $itemTransfer->getIdProductAbstract(),
            $this->localeClient->getCurrentLocale()
        );

        return $product ? $product['attributes'] : $itemTransfer->getConcreteAttributes();
    }

    /**
     * @param array $attributes
     *
     * @return bool
     */
    protected function isAttributesProvidedForBenefitVoucher(array $attributes): bool
    {
        $benefitStoreKey = $this->checkoutPageConfig->getBenefitStoreKey();
        $benefitSalesPriceKey = $this->checkoutPageConfig->getBenefitSalesPriceKey();
        $benefitAmountKey = $this->checkoutPageConfig->getBenefitAmountKey();

        return isset($attributes[$benefitAmountKey])
            && isset($benefitSalesPriceKey)
            && isset($attributes[$benefitStoreKey]);
    }
}
