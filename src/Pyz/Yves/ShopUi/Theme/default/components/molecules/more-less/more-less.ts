import Component from 'ShopUi/models/component';

export default class MoreLess extends Component {
    protected trigger: HTMLButtonElement;
    protected triggerText: HTMLElement;
    protected target: HTMLElement;

    protected readyCallback(): void {}

    protected init(): void {
        this.trigger = <HTMLButtonElement>this.getElementsByClassName(`${this.jsName}__button`)[0];
        this.triggerText = <HTMLElement>this.trigger.getElementsByClassName(`${this.jsName}__button-text`)[0];
        this.target = <HTMLElement>document.getElementsByClassName(this.targetClassName)[0];

        this.mapEvents();
    }

    protected mapEvents(): void {
        this.trigger.addEventListener('click', () => this.onTriggerEvent());
    }

    protected onTriggerEvent(): void {
        this.toggleTrigger();
        this.toggleTarget();
    }

    protected toggleTrigger(): void {
        if (!this.trigger.classList.contains(this.classActive)) {
            this.trigger.classList.add(this.classActive);
            this.triggerText.innerText = this.textLess;

            return;
        }

        this.trigger.classList.remove(this.classActive);
        this.triggerText.innerText = this.textMore;
    }

    protected toggleTarget(): void {
        if (!this.target.classList.contains(this.classToToggle)) {
            this.target.classList.add(this.classToToggle);

            return;
        }

        this.target.classList.remove(this.classToToggle);
    }

    protected get textMore(): string {
        return this.getAttribute('data-text-more');
    }

    protected get textLess(): string {
        return this.getAttribute('data-text-less');
    }

    protected get targetClassName(): string {
        return this.getAttribute('data-target-class-name');
    }

    protected get classToToggle(): string {
        return this.getAttribute('class-to-toggle');
    }

    protected get classToToggleHeight(): string {
        return this.getAttribute('class-to-toggle-height');
    }

    protected get classActive(): string {
        return this.getAttribute('class-active');
    }
}
