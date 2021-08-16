import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class BenefitDealSummary extends Component {
    protected useShoppingPointSelector: string;
    protected useShoppingPointIdRegex: RegExp;
    protected finalAmountSelector: string;
    protected recalculateRoute: string;
    protected benefitVouchersAmountSelector: string;
    protected totalUsedShoppingPointsSelector: string;
    protected readyCallback(): void {
    }

    protected init(): void {
        this.useShoppingPointSelector = '.benefit_deal_use_shopping_points';
        this.useShoppingPointIdRegex = /benefit_deal_collection_form_benefitItems_(.*)_useShoppingPoints/;
        this.benefitVouchersAmountSelector = '#benefit_deal_collection_form_totalUsedBenefitVouchersAmount';
        this.recalculateRoute = '/calculation/recalculate';
        this.finalAmountSelector = '#benefit-deal-summary__final-amount';
        this.totalUsedShoppingPointsSelector = '.benefit-deal-summary__total-used-shopping-points';
        $(this.useShoppingPointSelector).prop('checked', true);
        this.recalculatePriceToPay();
        this.onInputChange();
    }

    protected onInputChange(): void {
        let self = this;
        let timer, delay = 750;
        $(self.benefitVouchersAmountSelector).on('input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                self.recalculatePriceToPay();
            }, delay);
        });
    }

    protected recalculatePriceToPay(): void {
        let self = this;
        $.post(self.recalculateRoute, self.getRecalculateCallData(), function(data) {
            $(self.finalAmountSelector).text(data.totals_formatted.price_to_pay);
            self.updateTotalUsedShoppingPoints(data);
            $(self.benefitVouchersAmountSelector).val(data.total_used_benefit_vouchers_amount / 100);
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
