<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPageNO\Form\Steps;

use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;
use Pyz\Yves\CheckoutPage\Form\Steps\PaymentForm as PyzPaymentForm;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class PaymentForm extends PyzPaymentForm
{
    /**
     * @return callable[]
     */
    protected function getFormFieldMethodMap(): array
    {
        return [
            $this->getConfig()->getPaymentOptionIdEVoucherOnBehalfOfMarketer() =>
                function (FormBuilderInterface $builder, CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer): void {
                    $this->addUseEVoucherOnBehalfOfMarketerSubForm($builder, $balanceByCurrencyTransfer);
                },
            $this->getConfig()->getPaymentOptionIdCashback() =>
                function (FormBuilderInterface $builder, CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer): void {
                    $this->addUseCashbackBalanceSubForm($builder, $balanceByCurrencyTransfer);
                },
        ];
    }
}
