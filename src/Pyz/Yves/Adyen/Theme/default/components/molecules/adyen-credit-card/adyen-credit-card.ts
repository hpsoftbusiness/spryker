/* tslint:disable: no-any */
declare const AdyenCheckout: any;
/* tslint:enable: no-any */

import Component from 'ShopUi/models/component';
import ScriptLoader from 'ShopUi/components/molecules/script-loader/script-loader';

interface PaymentState {
    data: {
        paymentMethod: {
            encryptedCardNumber: string;
            encryptedSecurityCode: string;
            encryptedExpiryYear: string;
            encryptedExpiryMonth: string;
        };
    };
    isValid: boolean;
}

export default class AdyenCreditCard extends Component {
    protected scriptLoader: ScriptLoader;
    protected cardNumberInput: HTMLInputElement;
    protected securityCodeInput: HTMLInputElement;
    protected expiryYearInput: HTMLInputElement;
    protected expiryMonthInput: HTMLInputElement;
    protected submitButton: HTMLButtonElement;
    protected paymentMethodTriggers: HTMLInputElement[];
    protected isFormValid: boolean = false;

    protected readyCallback(): void {}

    protected init(): void {
        this.cardNumberInput = <HTMLInputElement>this.querySelector(this.cardNumberSelector);
        this.securityCodeInput = <HTMLInputElement>this.querySelector(this.securityCodeSelector);
        this.expiryYearInput = <HTMLInputElement>this.querySelector(this.expiryYearSelector);
        this.expiryMonthInput = <HTMLInputElement>this.querySelector(this.expiryMonthSelector);
        this.scriptLoader = <ScriptLoader>this.getElementsByClassName(`${this.jsName}__script-loader`)[0];
        this.submitButton = <HTMLButtonElement>document.getElementsByClassName(this.submitButtonClassName)[0];
        this.paymentMethodTriggers = <HTMLInputElement[]>Array.from(
            document.getElementsByClassName(this.paymentMethodTriggerClassName)
        );

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.onScriptLoad();
        this.onPaymentMethodTriggerChange();
    }

    protected onPaymentMethodTriggerChange(): void {
        this.paymentMethodTriggers.forEach((trigger: HTMLInputElement) => {
            trigger.addEventListener('change', () => {
                this.toggleSubmitButtonStateOnPaymentChange(trigger);
            });

            this.disableSubmitButtonOnLoad(trigger);
        });
    }

    protected toggleSubmitButtonStateOnPaymentChange(trigger: HTMLInputElement): void {
        if (trigger.value !== this.creditCardTriggerValue) {
            this.submitButtonState = false;

            return;
        }

        this.submitButtonState = !this.isFormValid;
    }

    protected disableSubmitButtonOnLoad(trigger: HTMLInputElement): void {
        if ((trigger.value === this.creditCardTriggerValue) && trigger.checked) {
            this.submitButtonState = true;
        }
    }

    protected onScriptLoad(): void {
        this.scriptLoader.addEventListener('scriptload', () => this.createCheckout());
    }

    protected createCheckout(): void {
        const configuration = {
            locale: this.locale,
            environment: this.environment,
            originKey: this.originKey,
            paymentMethodsResponse: JSON.parse(this.paymentMethodsResponse),
            onChange: (state: PaymentState) => {
                this.onPaymentFormFieldsChange(state);
            },
        };

        const checkout = new AdyenCheckout(configuration);
        checkout.create('card').mount(`#${this.containerId}`);
    }

    protected onPaymentFormFieldsChange(state: PaymentState): void {
        const paymentMethod = state.data.paymentMethod;

        this.isFormValid = state.isValid;
        this.submitButtonState = !this.isFormValid;

        if (!this.isFormValid) {
            this.fillFormHiddenFields();

            return;
        }

        this.fillFormHiddenFields(
            paymentMethod.encryptedCardNumber,
            paymentMethod.encryptedSecurityCode,
            paymentMethod.encryptedExpiryYear,
            paymentMethod.encryptedExpiryMonth,
        );
    }

    protected fillFormHiddenFields(
        cardNumber: string = '',
        securityCode: string = '',
        expiryYear: string = '',
        expiryMonth: string = ''
    ): void {
        this.cardNumberInput.value = cardNumber;
        this.securityCodeInput.value = securityCode;
        this.expiryYearInput.value = expiryYear;
        this.expiryMonthInput.value = expiryMonth;
    }

    protected set submitButtonState(state: boolean) {
        this.submitButton.disabled = state;
    }

    protected get originKey(): string {
        return this.getAttribute('origin-key');
    }

    protected get paymentMethodsResponse(): string {
        return this.getAttribute('payment-methods-response');
    }

    protected get containerId(): string {
        return this.getAttribute('container-id');
    }

    protected get locale(): string {
        return this.getAttribute('locale');
    }

    protected get environment(): string {
        return this.getAttribute('environment');
    }

    protected get cardNumberSelector(): string {
        return this.getAttribute('cart-number-selector');
    }

    protected get securityCodeSelector(): string {
        return this.getAttribute('security-code-selector');
    }

    protected get expiryYearSelector(): string {
        return this.getAttribute('expiry-year-selector');
    }

    protected get expiryMonthSelector(): string {
        return this.getAttribute('expiry-month-selector');
    }

    protected get submitButtonClassName(): string {
        return this.getAttribute('submit-button-class-name');
    }

    protected get paymentMethodTriggerClassName(): string {
        return this.getAttribute('payment-method-trigger-class-name');
    }

    protected get creditCardTriggerValue(): string {
        return this.getAttribute('credit-card-trigger-value');
    }
}
