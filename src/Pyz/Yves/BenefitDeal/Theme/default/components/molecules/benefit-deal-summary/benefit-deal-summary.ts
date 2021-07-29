import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class BenefitDealSummary extends Component {
        protected useShoppingPointSelector: string;
        protected useShoppingPointIdRegex: RegExp;
        protected benefitVouchersAmountId: string;
        protected benefitVouchersAmountSelector: string;
        protected recalculateRoute: string;
        protected finalAmountSelector: string;
        protected invoke_make_blank: boolean;
        protected totalUsedShoppingPointsSelector: string;
        protected readyCallback(): void {
    }

    protected init(): void {
        this.useShoppingPointSelector = '.benefit_deal_use_shopping_points';
        this.useShoppingPointIdRegex = /benefit_deal_collection_form_benefitItems_(.*)_useShoppingPoints/;
        this.benefitVouchersAmountId = 'benefit_deal_collection_form_totalUsedBenefitVouchersAmount';
        this.benefitVouchersAmountSelector = '#' + this.benefitVouchersAmountId;
        this.recalculateRoute = '/calculation/recalculate';
        this.finalAmountSelector = '#benefit-deal-summary__final-amount';
        this.totalUsedShoppingPointsSelector = '.benefit-deal-summary__total-used-shopping-points';
        $(this.useShoppingPointSelector).prop('checked', true);
        this.recalculatePriceToPay();
        this.onInputChange();
        this.invoke_make_blank = true;
    }

    protected setBenefitVoucherDefaultPointAmount(): void {
        if (this.invoke_make_blank)
        {
            var youHave = Math.floor($('#benefitVoucherAmount').val()/100);

            if (Number.isNaN(youHave)) {
                youHave = 0;
            }

            var total = $('#total').val();

            if (total < youHave) {
                $('#benefit_deal_collection_form_totalUsedBenefitVouchersAmount').val(total);
            }
            else {
                $('#benefit_deal_collection_form_totalUsedBenefitVouchersAmount').val(youHave);
            }
            this.invoke_make_blank = false;
        }
    }

    protected onInputChange(): void {
        let self = this;
        $(self.benefitVouchersAmountSelector).on('input', function() {
            self.recalculatePriceToPay();
        });
    }

    protected recalculatePriceToPay(): void {
        let self = this;
        $.post(self.recalculateRoute, self.getRecalculateCallData(), function(data) {
            $(self.finalAmountSelector).text(data.totals_formatted.price_to_pay);
            self.updateTotalUsedShoppingPoints(data);
            document.getElementById(self.benefitVouchersAmountId).value = data.total_used_benefit_vouchers_amount / 100;
            self.setBenefitVoucherDefaultPointAmount();
        });
    }

    protected getRecalculateCallData(): string {
        let self = this,
            callData = {
                items: {},
                total_used_benefit_voucher_amount: $(this.benefitVouchersAmountSelector).val(),
                use_benefit_voucher: true
            };

        $(self.useShoppingPointSelector).each(function() {
            let useShoppingPointKey = $(this).attr('id').replace(self.useShoppingPointIdRegex, '$1');
            callData['items'][useShoppingPointKey] = {
                use_shopping_point: true
            };
        });
        return JSON.stringify(callData);
    }

    protected updateTotalUsedShoppingPoints(data): void
    {
        $(this.totalUsedShoppingPointsSelector).text(data.total_used_shopping_points_amount);
    }
}


