<?php

/**
 * This file is part of the Spryker Commerce OS.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Pyz\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer;
use Pyz\Yves\CheckoutPage\Form\Validation\PaymentSelectionValidationGroupResolver;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface;
use SprykerShop\Yves\CheckoutPage\Form\Steps\PaymentForm as SpyPaymentForm;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 * @method \Pyz\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class PaymentForm extends SpyPaymentForm
{
    public const OPTION_KEY_CUSTOMER_BALANCES = 'customer_balances';

    public const OPTION_KEY_CURRENCY_CODE = 'currency_code';

    public const FORM_NAME = 'myWorldPaymentForm';

    private const FORM_FIELD_USE_EVOUCHER_BALANCE = 'useEVoucherBalance';

    private const FORM_FIELD_USE_EVOUCHER_ON_BEHALF_OF_MARKETER = 'useEVoucherOnBehalfOfMarketer';

    private const FORM_FIELD_USE_CASHBACK_BALANCE = 'useCashbackBalance';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        if (!$this->getConfig()->isCashbackFeatureEnabled()) {
            return;
        }

        /**
         * @var \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer[] $customerBalances
         */
        $customerBalances = $options[self::OPTION_KEY_CUSTOMER_BALANCES] ?? null;
        if (!$customerBalances) {
            return;
        }

        $subFormMethodsByPaymentOptionIdMap = $this->getFormFieldMethodMap();
        foreach ($customerBalances as $customerBalance) {
            if (!$this->isDiscountBalanceApplicable($customerBalance)) {
                continue;
            }

            $subFormMethodsByPaymentOptionIdMap[$customerBalance->getPaymentOptionId()]($builder, $customerBalance);
        }
    }

    /**
     * @param \Symfony\Component\Form\FormView $view
     * @param \Symfony\Component\Form\FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$this->getConfig()->isCashbackFeatureEnabled()) {
            return;
        }

        /**
         * @var \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer[] $customerBalances
         */
        $customerBalances = $options[self::OPTION_KEY_CUSTOMER_BALANCES];
        $converter = $this->getDecimalToIntegerConverter();
        $balanceTypeToAmountMap = [];
        foreach ($customerBalances as $balanceByCurrencyTransfer) {
            if (!$this->isDiscountBalanceApplicable($balanceByCurrencyTransfer)) {
                continue;
            }
            $balanceTypeToAmountMap[$balanceByCurrencyTransfer->getPaymentOptionName()] =
                $converter->convert($balanceByCurrencyTransfer->getTargetAvailableBalance()->toFloat());
        }

        $view->vars[self::OPTION_KEY_CUSTOMER_BALANCES] = $balanceTypeToAmountMap;
        $view->vars[self::OPTION_KEY_CURRENCY_CODE] = $options[self::OPTION_KEY_CURRENCY_CODE];
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        if (!$this->getConfig()->isCashbackFeatureEnabled()) {
            return;
        }

        $resolver->setRequired(self::OPTION_KEY_CUSTOMER_BALANCES);
        $resolver->setRequired(self::OPTION_KEY_CURRENCY_CODE);
        $resolver->setDefaults(['validation_groups' => $this->getValidationGroupResolver()]);
    }

    /**
     * @return \Pyz\Yves\CheckoutPage\Form\Validation\PaymentSelectionValidationGroupResolver
     */
    private function getValidationGroupResolver(): PaymentSelectionValidationGroupResolver
    {
        return $this->getFactory()->createPaymentSelectionValidationGroupResolver();
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer
     *
     * @return void
     */
    protected function addUseEVoucherBalanceSubForm(
        FormBuilderInterface $builder,
        CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer
    ): void {
        $builder->add(
            self::FORM_FIELD_USE_EVOUCHER_BALANCE,
            CheckboxType::class,
            [
                'label' => ' ',
                'required' => false,
                'disabled' => !$this->assertHasBalance($balanceByCurrencyTransfer),
            ]
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer
     *
     * @return void
     */
    protected function addUseEVoucherOnBehalfOfMarketerSubForm(
        FormBuilderInterface $builder,
        CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer
    ): void {
        $builder->add(
            self::FORM_FIELD_USE_EVOUCHER_ON_BEHALF_OF_MARKETER,
            CheckboxType::class,
            [
                'label' => ' ',
                'required' => false,
                'disabled' => !$this->assertHasBalance($balanceByCurrencyTransfer),
            ]
        );
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer
     *
     * @return void
     */
    protected function addUseCashbackBalanceSubForm(
        FormBuilderInterface $builder,
        CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer
    ): void {
        $builder->add(
            self::FORM_FIELD_USE_CASHBACK_BALANCE,
            CheckboxType::class,
            [
                'label' => ' ',
                'required' => false,
                'disabled' => !$this->assertHasBalance($balanceByCurrencyTransfer),
            ]
        );
    }

    /**
     * @return callable[]
     */
    protected function getFormFieldMethodMap(): array
    {
        return [
            $this->getConfig()->getPaymentOptionIdEVoucher() =>
                function (FormBuilderInterface $builder, CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer): void {
                    $this->addUseEVoucherBalanceSubForm($builder, $balanceByCurrencyTransfer);
                },
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

    /**
     * @param \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer
     *
     * @return bool
     */
    private function isDiscountBalanceApplicable(CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer): bool
    {
        return array_key_exists($balanceByCurrencyTransfer->getPaymentOptionId(), $this->getFormFieldMethodMap());
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer
     *
     * @return bool
     */
    private function assertHasBalance(CustomerBalanceByCurrencyTransfer $balanceByCurrencyTransfer): bool
    {
        return $balanceByCurrencyTransfer->getTargetAvailableBalance()->greaterThan(0);
    }

    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    private function getDecimalToIntegerConverter(): DecimalToIntegerConverterInterface
    {
        return $this->getFactory()->createCheckoutFormFactory()->createDecimalToIntegerConverter();
    }
}
