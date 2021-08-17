import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class MyworldPayment extends Component {
    protected $checkboxes;

    protected readyCallback(): void {
    }

    protected init(): void {
        this.$checkboxes = $('.myworld-payment input[type="checkbox"]');

        this.onCheckboxChange();
        this.triggerInternalPaymentSelectedEvent();
    }

    protected onCheckboxChange(): void {
        let self = this;

        self.$checkboxes.change(function() {
            self.oneCheckboxAllowed($(this));
            self.triggerInternalPaymentSelectedEvent();
        });
    }

    protected oneCheckboxAllowed($checkboxChanged): void {
        let isChecked = $checkboxChanged.prop('checked');
        if (isChecked) {
            this.$checkboxes.prop('checked', false);
            $checkboxChanged.prop('checked', true);
        }
    }

    protected triggerInternalPaymentSelectedEvent(): void {
        const checkboxChecked = this.$checkboxes.filter(':checked');
        let isGrandTotalCovered = false;

        if (checkboxChecked.length) {
            const selectedInternalPaymentBalance = this.getSelectedInternalPaymentBalance(checkboxChecked);
            isGrandTotalCovered = this.hasInternalPaymentFullAmount(selectedInternalPaymentBalance);
        }

        const internalPaymentSelectedEvent = this.createInternalPaymentSelectedEvent(isGrandTotalCovered);
        document.dispatchEvent(internalPaymentSelectedEvent);
    }

    protected getSelectedInternalPaymentBalance(checkbox: JQuery): number {
        return checkbox.parents('.myworld-payment-method__container')
            .find($('.myworld-payment-method__amount'))
            .data('amount');
    }

    protected createInternalPaymentSelectedEvent(grandTotalCovered: boolean): CustomEvent {
        return new CustomEvent('internalPaymentSelected', {detail: grandTotalCovered});
    }

    protected hasInternalPaymentFullAmount(internalPaymentBalance: number): boolean {
        const priceToPay = this.getPriceToPay();

        return internalPaymentBalance >= priceToPay;
    }

    protected getPriceToPay(): number {
        return $('.myworld-payment__container').data('price-to-pay');
    }
}

