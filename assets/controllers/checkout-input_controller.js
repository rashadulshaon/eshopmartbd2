import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['quantity', 'subtractButton', 'subtractButtonLoader', 'addButton', 'addButtonLoader', 'itemPrice'];
    static values = { id: Number, price: Number };

    async add() {
        this.quantityTarget.value++;
        const apiUrl = `/checkout_update/add/${this.idValue}`;
        this.addButtonTarget.classList.add('d-none');
        this.addButtonLoaderTarget.classList.remove('d-none');
        const response = await fetch(apiUrl);
        this.addButtonTarget.classList.remove('d-none');
        this.addButtonLoaderTarget.classList.add('d-none');
        this.itemPriceTarget.innerHTML = Number(this.itemPriceTarget.innerHTML) + this.priceValue;

        let totalPrice = document.getElementById('totalPrice').innerHTML;
        document.getElementById('totalPrice').innerHTML = Number(totalPrice) + Number(this.priceValue);
    }

    async subtract() {
        if (this.quantityTarget.value > 1) {
            this.quantityTarget.value--;
            const apiUrl = `/checkout_update/substract/${this.idValue}`;
            this.subtractButtonTarget.classList.add('d-none');
            this.subtractButtonLoaderTarget.classList.remove('d-none');
            const response = await fetch(apiUrl);
            this.subtractButtonTarget.classList.remove('d-none');
            this.subtractButtonLoaderTarget.classList.add('d-none');
            this.itemPriceTarget.innerHTML = Number(this.itemPriceTarget.innerHTML) - this.priceValue;

            let totalPrice = document.getElementById('totalPrice').innerHTML;
            document.getElementById('totalPrice').innerHTML = Number(totalPrice) - Number(this.priceValue);
        }
    }
}