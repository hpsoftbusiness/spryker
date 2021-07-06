import  SprykerAdyenCreditCard from 'SprykerEcoAdyen/components/molecules/adyen-credit-card/adyen-credit-card';

export default class AdyenCreditCard extends SprykerAdyenCreditCard {
    protected validationEnabled: boolean = true;

    protected mapEvents(): void {
        this.onInternalPaymentTriggerChange();

        super.mapEvents();
    }

    protected onInternalPaymentTriggerChange(): void {
        document.addEventListener('internalPaymentSelected', (event: CustomEvent) => {
            const isGrandTotalCoveredByInternalPaymentBalance = event.detail;
            this.validationEnabled = !isGrandTotalCoveredByInternalPaymentBalance;

            const selectedPaymentMethodTrigger = this.findSelectedPaymentMethodTrigger();
            this.toggleSubmitButtonStateOnPaymentChange(selectedPaymentMethodTrigger);
        })
    }

    protected findSelectedPaymentMethodTrigger(): HTMLInputElement {
        return this.paymentMethodTriggers.find((trigger: HTMLInputElement) => {
            return trigger.checked;
        });
    }

    protected set submitButtonState(state: boolean) {
        this.submitButton.disabled = state && this.validationEnabled;
    }
}
