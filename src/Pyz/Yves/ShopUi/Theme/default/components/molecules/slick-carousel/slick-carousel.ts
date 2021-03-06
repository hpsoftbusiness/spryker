import Component from 'ShopUi/models/component';
import CustomSelect from '../custom-select/custom-select';
import LazyImage from '../lazy-image/lazy-image';
import $ from 'jquery/dist/jquery';
import 'slick-carousel';

export default class SlickCarousel extends Component {
    protected container: HTMLElement;
    protected $container: $;
    protected customSelects: CustomSelect[];
    protected isFirstClick = true;

    protected readyCallback(): void {}

    protected init(): void {
        this.container = <HTMLElement>this.getElementsByClassName(`${this.jsName}__container`)[0];
        this.$container = $(this.container);

        if (this.customSelectClassName) {
            this.customSelects = <CustomSelect[]>Array.from(this.getElementsByClassName(this.customSelectClassName));
        }

        this.initialize();
    }

    protected initialize(): void {
        this.$container.on('init', () => {
            this.container.style.visibility = 'visible';

            if (this.customSelects) {
                this.customSelects.forEach((select: CustomSelect) => {
                    select.initSelect();
                    select.changeSelectEvent();
                });
            }
        });

        this.$container.on('afterChange', () => {
            if (!this.isFirstClick) {
                return;
            }
            this.isFirstClick = false;
            const clonedSlides = <HTMLElement[]>Array.from(this.container.getElementsByClassName('slick-cloned'));
            clonedSlides.forEach((item: HTMLElement) => {
                const lazyImage = <LazyImage>item.getElementsByClassName('lazy-image')[0];
                if (!lazyImage) {
                    return;
                }
                lazyImage.init();
                lazyImage.updateImage();
            });
        });

        this.$container.slick(
            this.sliderConfig
        );

        if ('ontouchstart' in document.documentElement){
            this.$container.slick('slickPause');
        }
    }

    protected get customSelectClassName(): string {
        return this.getAttribute('custom-select-class-name');
    }

    protected get sliderConfig(): object {
        return JSON.parse(this.getAttribute('data-json'));
    }
}
