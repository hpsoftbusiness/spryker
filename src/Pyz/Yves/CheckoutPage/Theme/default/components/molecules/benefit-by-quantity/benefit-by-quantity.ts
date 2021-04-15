import Component from 'ShopUi/models/component';

export default class BenefitByQuantity extends Component {
    protected checkboxes: HTMLFormElement[];
    protected selectedOptionClasses: string;
    protected selectedOptionModifier: string;
    protected placeholder: HTMLElement;

    protected readyCallback(): void {
    }

    protected init() {
        this.checkboxes = <HTMLFormElement[]>Array.from(this.getElementsByClassName(this.getCheckboxClasses()));
        this.selectedOptionModifier = '--selected';
        this.selectedOptionClasses = `${this.getOptionClasses()}${this.selectedOptionModifier}`;
        this.placeholder = <HTMLElement>this.getElementsByClassName(this.getPlaceholderClasses())[0];
        this.checkboxes[0].checked = true;
        this.mapEvents();
    }

    protected mapEvents(): void {
        this.handleInitialOption();
        this.chooseOption();
    }

    protected handleInitialOption(): void {
        this.checkboxes[0].nextElementSibling.classList.add(this.selectedOptionClasses);
        this.placeholder.innerHTML = this.checkboxes[0].nextElementSibling.innerHTML;
    }

    protected chooseOption(): void {
        this.checkboxes.map(checkbox => {
            checkbox.addEventListener('change', event => {
                const target = <HTMLFormElement>event.currentTarget;
                const selectedOption = this.getElementsByClassName(this.selectedOptionClasses)[0];
                if (selectedOption) {
                    selectedOption.classList.remove(this.selectedOptionClasses);
                }

                target.nextElementSibling.classList.add(this.selectedOptionClasses);
                this.placeholder.innerHTML = target.nextElementSibling.innerHTML;
            });
        });
    }

    protected getCheckboxClasses(): string {
        return this.getAttribute('checkbox-classes');
    }

    protected getOptionClasses(): string {
        return this.getAttribute('option-classes');
    }

    protected getPlaceholderClasses(): string {
        return this.getAttribute('placeholder-classes');
    }
}
