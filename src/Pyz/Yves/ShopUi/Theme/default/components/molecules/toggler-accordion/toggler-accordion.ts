import Component from 'ShopUi/models/component';

export default class TogglerAccordion extends Component {
    protected triggers: HTMLElement[];

    protected readyCallback(): void {}

    protected init(): void {
        this.triggers = <HTMLElement[]>Array.from(document.getElementsByClassName(this.triggerClassName));
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.triggers.forEach(trigger => trigger.addEventListener('click', this.triggerHandler.bind(this, trigger)));
        document.body.addEventListener('click', (event: Event) => this.outsideClickListener(event));
    }

    protected triggerHandler(trigger: HTMLElement): void {
        const togglerContent = document.getElementsByClassName(
            trigger.getAttribute('data-toggle-target-class-name')
        )[0];
        trigger.classList.toggle(this.activeClass);
        togglerContent.classList.toggle(this.toggleClass);
    }

    protected outsideClickListener(event: Event): void {
        if (!this.isClosedOutside) {
            return;
        }

        const isVisible = elem => !!elem && !!(elem.offsetWidth || elem.offsetHeight || elem.getClientRects().length);

        this.triggers.forEach((element: HTMLElement) => {
            const togglerContent = document.getElementsByClassName(
                element.getAttribute('data-toggle-target-class-name')
            )[0];

            if (!element.contains(event.target) &&
                isVisible(element) &&
                element.classList.contains(this.activeClass) &&
                !togglerContent.contains(event.target) &&
                isVisible(togglerContent) &&
                !togglerContent.classList.contains(this.toggleClass)
            ) {
                element.classList.remove(this.activeClass);
                togglerContent.classList.add(this.toggleClass);
            }
        });
    }

    protected get triggerClassName(): string {
        return this.getAttribute('trigger-class-name');
    }

    protected get toggleClass(): string {
        return this.getAttribute('class-to-toggle');
    }

    protected get activeClass(): string {
        return this.getAttribute('active-class');
    }

    protected get isClosedOutside(): boolean {
        return this.hasAttribute('is-closed-outside');
    }
}
