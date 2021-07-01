import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class BenefitDealSummary extends Component {
    protected useShoppingPointSelector: string;
    protected useShoppingPointIdRegex: RegExp;
    protected benefitVouchersAmountId: string;
    protected benefitVouchersAmountSelector: string;
    protected recalculateRoute: string;
    protected finalAmountSelector: string;
    protected totalUsedShoppingPointsSelector: string;
    protected useBenefitVoucherSelector: string;

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
        this.useBenefitVoucherSelector = '#benefit_deal_collection_form_useBenefitVoucher';

        this.enableDisableBenefitVouchersAmount();
        this.recalculatePriceToPay();
        this.onUseBenefitVoucherChange();
        this.onInputChange();
    }

    protected onInputChange(): void {
        let self = this;
        $(self.useShoppingPointSelector
            + ','
            + self.useBenefitVoucherSelector
            + ','
            + self.benefitVouchersAmountSelector
        ).on('input', function() {
            self.recalculatePriceToPay();
        });
    }

    protected onUseBenefitVoucherChange(): void {
        let self = this;
        $(self.useBenefitVoucherSelector).on('input', function() {
            self.enableDisableBenefitVouchersAmount();
        });
    }

    protected enableDisableBenefitVouchersAmount(): void {
        let self = this,
        useBenefitVoucher = $(self.useBenefitVoucherSelector).prop('checked');
        if (useBenefitVoucher) {
            $(self.benefitVouchersAmountSelector).removeAttr('disabled');
        } else {
            $(self.benefitVouchersAmountSelector).attr('disabled', 'disabled');
        }
    }

    protected recalculatePriceToPay(): void {
        let self = this;
        $.post(self.recalculateRoute, self.getRecalculateCallData(), function(data) {
            $(self.finalAmountSelector).text(data.totals_formatted.price_to_pay);
            self.updateTotalUsedShoppingPoints(data);
            document.getElementById(self.benefitVouchersAmountId).value = data.total_used_benefit_vouchers_amount / 100;
        });
    }

    protected getRecalculateCallData(): string {
        let self = this,
            callData = {
                items: {},
                total_used_benefit_voucher_amount: $(this.benefitVouchersAmountSelector).val(),
                use_benefit_voucher: $(this.useBenefitVoucherSelector).prop('checked')
            };

        $(self.useShoppingPointSelector).each(function() {
            let useShoppingPointKey = $(this).attr('id').replace(self.useShoppingPointIdRegex, '$1');
            callData['items'][useShoppingPointKey] = {
                use_shopping_point: $(this).prop('checked')
            };
        });
        return JSON.stringify(callData);
    }

    protected updateTotalUsedShoppingPoints(data): void
    {
        let self = this;
        let totalUsedShoppingPoints = data.items.reduce(function (sum, currentItem) {
            let shoppingPointsQty = 0;
            if (currentItem.use_shopping_points === true) {
                shoppingPointsQty = currentItem.shopping_points_deal.shopping_points_quantity;
            }
            return sum + shoppingPointsQty;
        }, 0)
        $(self.totalUsedShoppingPointsSelector).text(totalUsedShoppingPoints);
    }
}

