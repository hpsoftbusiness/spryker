import Haven from '@chiiya/haven';
import Component from 'ShopUi/models/component';

export default class GdprTag extends Component {
    protected readyCallback(): void {}

    protected init(): void {
        Haven.create({
            services: [
                {
                    name: 'cookies',
                    purposes: ['functional']
                }
            ]
        });
    }


}
