import LazyImageCore from 'ShopUi/components/molecules/lazy-image/lazy-image';
import { EVENT_ELEMENT_IN_VIEWPORT } from 'ShopUi/components/molecules/viewport-intersection-observer/viewport-intersection-observer';

export default class LazyImage extends LazyImageCore {
    init(): void {
        super.init();
    }

    protected mapTriggerCustomViewportEvent(): void {
        this.addEventListener(EVENT_ELEMENT_IN_VIEWPORT, () => this.updateImage());
    }

    updateImage(): void {
        if (this.image) {
            this.imageSrc = this.image.dataset.src;

            return;
        }
        if (this.background) {
            this.backgroundImage = this.background.dataset.backgroundImage;
        }
    }
}
