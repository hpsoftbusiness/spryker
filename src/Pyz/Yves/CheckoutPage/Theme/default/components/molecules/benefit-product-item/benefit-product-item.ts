import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class BenefitProductItem extends Component {
    protected $button;

    protected readyCallback(): void {
    }

    protected init(): void {
        this.$button = $('.order-overview-removal-product-link');
        this.onRemoveClick();
    }

    protected onRemoveClick(): void {
        this.$button.off().click(function(e) {
            let url = this.dataset.url;
            $.ajax({
                url: url,
                type: "POST",
                dataType: "JSON",
                contentType: "application/json"
            }).done(function(data) {
                location.reload();
            });
        });
    }
}
