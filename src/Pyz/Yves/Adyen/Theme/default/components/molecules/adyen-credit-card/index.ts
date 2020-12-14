import './adyen-credit-card.scss';
import register from 'ShopUi/app/registry';
export default register('adyen-credit-card', () => import(
    /* webpackMode: "lazy" */
    /* webpackChunkName: "adyen-credit-card" */
    './adyen-credit-card'));
