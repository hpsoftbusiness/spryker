import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class MyworldPayment extends Component {
    protected $checkboxes;

    protected readyCallback(): void {
    }

    protected init(): void {
        this.$checkboxes = $('.myworld-payment input[type="checkbox"]');

        this.onCheckboxChange();
        this.toggleCreditCardPayment();
    }

    protected onCheckboxChange(): void {
        let self = this;

        self.$checkboxes.change(function() {
            self.oneCheckboxAllowed($(this));
            self.toggleCreditCardPayment();
        });
    }

    protected oneCheckboxAllowed($checkboxChanged): void {
        let isChecked = $checkboxChanged.prop('checked');
        if (isChecked) {
            this.$checkboxes.prop('checked', false);
            $checkboxChanged.prop('checked', true);
        }
    }

    protected toggleCreditCardPayment(): void {
        let $checkboxChecked = this.$checkboxes.filter(':checked'),
            $adyenCreditCardRadio = $('input[value="adyenCreditCard"]'),
            $adyenCreditCardForm = $adyenCreditCardRadio.parents('ul.list-switches').next('.form');

        if (!$adyenCreditCardRadio.length) {
            return;
        }

        if ($checkboxChecked.length && this.hasInternalPaymentFullAmount($checkboxChecked)) {
            $adyenCreditCardRadio.prop('checked', false);
            $adyenCreditCardRadio.prop('disabled', true);
            $adyenCreditCardForm.addClass('is-hidden');
        } else {
            $adyenCreditCardRadio.prop('disabled', false);
            $adyenCreditCardRadio.click();
            $adyenCreditCardForm.removeClass('is-hidden');
        }
    }

    protected hasInternalPaymentFullAmount($checkboxChecked): boolean {
        let grandTotal = $('.myworld-payment__container').data('grand-total'), // amount is in minor currency
            checkboxCheckedBalance = $checkboxChecked.parents('.myworld-payment-method__container')
                .find('.myworld-payment-method__amount')
                .data('amount') * 100; // * 100, because amount is in major currency
        return checkboxCheckedBalance >= grandTotal;
    }
}

