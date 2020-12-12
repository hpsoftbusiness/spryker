import './more-less.scss';
import register from 'ShopUi/app/registry';
export default register('more-less', () => import(/* webpackMode: "lazy" */'./more-less'));
