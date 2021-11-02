import SprykerAdyenCreditCard from 'SprykerEcoAdyen/components/molecules/adyen-credit-card/adyen-credit-card';

export default class AdyenCreditCard extends SprykerAdyenCreditCard {
    protected mapEvents(): void {
        this.onInternalPaymentTriggerChange();
        super.mapEvents();
    }

    protected onInternalPaymentTriggerChange(): void {
        document.addEventListener('internalPaymentSelected', () => {
            const ePaymentTriggers = Array.from(document.querySelector('myworld-payment').querySelectorAll('input'));

            if (this.paymentMethodTriggers.some((trigger) => trigger.checked)) {
                return;
            }

            this.submitButton.disabled = ePaymentTriggers.every((trigger) => !trigger.checked);
        });
    }
}
