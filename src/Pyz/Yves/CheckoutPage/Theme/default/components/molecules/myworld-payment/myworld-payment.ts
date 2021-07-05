import Component from 'ShopUi/models/component';
import $ from 'jquery/dist/jquery';

export default class MyworldPayment extends Component {
    protected readyCallback(): void {
        this.oneCheckboxAllowed();
    }

    protected oneCheckboxAllowed(): void {
        let $checkboxes = $('.myworld-payment input[type="checkbox"]');
        $checkboxes.change(function() {
            let isChecked = $(this).prop('checked');
            if (isChecked) {
                $checkboxes.prop('checked', false);
                $(this).prop('checked', true);
            }
        });
    }
}

