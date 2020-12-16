import './gdpr-tag.scss';
import register from 'ShopUi/app/registry';
export default register('gdpr-tag', () => import(/* webpackMode: "lazy" */'./gdpr-tag'));
