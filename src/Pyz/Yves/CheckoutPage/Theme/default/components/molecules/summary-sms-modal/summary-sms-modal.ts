import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class SummarySmsModal extends Component {
    protected smsResendRoute: string;
    protected emailResendRoute: string;
    protected contactTypeRadioSelector: string;
    protected labelSmsAlreadySentSelector: string;
    protected labelEmailAlreadySentSelector: string;
    protected modalSelector: string;
    protected submit: string;
    protected modalBackgroundSelector: string;
    protected timerCounterDefaultValue : number;
    protected timerCounter : number;
    protected timerSelector : string;
    protected timerValueSelector : string;
    protected linkSelector : string;
    protected checkboxSelector: string;
    protected readyCallback(): void {
    }

    protected init(): void {
        this.smsResendRoute = '/my-world-payment/send-code-customer-by-sms';
        this.emailResendRoute = ''; //TODO Put email route here when it will be available
        this.contactTypeRadioSelector = "input[name='resendPin']:checked";
        this.submit = '#resendPinSubmit';
        this.labelSmsAlreadySentSelector = '#notification_already_sent_by_sms';
        this.labelEmailAlreadySentSelector = '#notification_already_sent_by_email';
        this.modalSelector = '.summary-sms-modal__modal';
        this.modalBackgroundSelector = '.summary-sms-modal__background';
        this.timerSelector = '#modal_timer';
        this.timerValueSelector = '#modal_timer_counter';
        this.timerCounterDefaultValue = 30;
        this.linkSelector = '.summary-sms-modal__link';
        this.checkboxSelector = '#modal';

        this.onSubmit();
    }

    protected onSubmit(): void {
        let self = this;
        $(self.submit).click(function() {
            self.resendPin();
        });
    }

    protected getResendMethodType(): string {
        let self = this;

        return $(self.contactTypeRadioSelector).val();
    }

    protected getResendUrl(): string {
        let self = this;
        let url = '';
        const methodeType = self.getResendMethodType();

        if ('resendPinByEmail' === methodeType) {
            url = self.emailResendRoute;
        } else if ('resendPinBySms' === methodeType) {
            url = self.smsResendRoute;
        }

        return url;
    }

    protected showSentNotification(): void {
        let self = this;
        const methodeType = self.getResendMethodType();
        if ('resendPinByEmail' === methodeType) {
            $(self.labelEmailAlreadySentSelector ).show().css("display","inline");
        } else if ('resendPinBySms' === methodeType) {
            $(self.labelSmsAlreadySentSelector).show().css("display","inline");
        }
        $(self.checkboxSelector).prop('checked',false);
        self.startTimer();
    }

    protected resendPin(): void {
        let self = this;
        let url = self.getResendUrl();
        if (url !== '') {
            $.ajax({
                type: "POST",
                url: url,
                success: function(){
                    self.showSentNotification();
                },
                error: function(XMLHttpRequest, textStatus) {
                    alert(XMLHttpRequest.responseJSON.message);
                }
            });
        }
    }

    protected startTimer() {
        let self = this;
        $(self.linkSelector).hide();
        $(self.timerSelector).show().css("display","inline");
        $(self.checkboxSelector).prop('disabled', true);
        self.timerCounter = self.timerCounterDefaultValue;
        let intervalId = setInterval(() => {
            self.timerCounter -= 1;
            $(self.timerValueSelector).text(String(self.timerCounter));
            if (this.timerCounter === 0){
                clearInterval(intervalId);
                $(self.timerValueSelector).text(String(self.timerCounterDefaultValue));
                $(self.timerSelector).hide();
                $(self.linkSelector).show().css("display","inline");
                $(self.checkboxSelector).prop('disabled', false);
            }
        }, 1000)
    }
}
