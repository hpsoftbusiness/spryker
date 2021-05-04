import './myworld-payment.scss';
import register from 'ShopUi/app/registry';
export default register('myworld-payment', () => import(/* webpackMode: "eager" */'./myworld-payment'));
