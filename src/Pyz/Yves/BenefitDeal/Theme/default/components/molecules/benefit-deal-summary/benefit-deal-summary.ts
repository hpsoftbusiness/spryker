import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class BenefitDealSummary extends Component {
    protected useShoppingPointSelector: string;
    protected useShoppingPointIdRegex: RegExp;
    protected finalAmountSelector: string;
    protected recalculateRoute: string;
    protected benefitVouchersAmountSelector: string;
    protected totalUsedShoppingPointsSelector: string;
    protected request: any;
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
        this.request = null;
        this.recalculatePriceToPay();
        this.onInputChange();
    }

    protected onInputChange(): void {
        let self = this;
        let timer, delay = 500;
        $(self.benefitVouchersAmountSelector).on('input', function() {
            clearTimeout(timer);
            timer = setTimeout(function() {
                self.recalculatePriceToPay();
            }, delay);
        });
    }

    protected recalculatePriceToPay(): void {
        let self = this;
        self.request = $.ajax({
            url: self.getLocaleFromUrl() +self.recalculateRoute,
            type: 'POST',
            data: self.getRecalculateCallData(),
            dataType: 'JSON',
            beforeSend : function() {
                if(self.request != null) {
                    self.request.abort();
                }
            },
            success: function(json) {
                self.updateFinalAmount(json)
                self.updateTotalUsedShoppingPoints(json);
                self.updateUsedBenefitVoucher(json);
            },
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

    protected updateFinalAmount(data): void
    {
        $(this.finalAmountSelector).text(data.totals_formatted.price_to_pay);
    }

    protected updateUsedBenefitVoucher(data): void
    {
        $(this.benefitVouchersAmountSelector).text(data.total_used_benefit_vouchers_amount / 100);
    }

    protected getLocaleFromUrl(): string
    {
        let countryUrlPrefix = '';
        let pathArray = window.location.pathname.split('/');
        if(typeof (pathArray[1]) === 'string' && pathArray[1].length == 2){
            countryUrlPrefix = '/' + pathArray[1];
        }

        return countryUrlPrefix;
    }
}
