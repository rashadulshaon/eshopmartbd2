import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['quantity', 'subtractButton', 'subtractButtonLoader', 'addButton', 'addButtonLoader', 'itemPrice'];
    static values = { id: Number, price: Number };

    async add() {
        this.quantityTarget.value++;
        const apiUrl = `/cart_update/add/${this.idValue}`;
        this.addButtonTarget.classList.add('d-none');
        this.addButtonLoaderTarget.classList.remove('d-none');
        const response = await fetch(apiUrl);
        this.addButtonTarget.classList.remove('d-none');
        this.addButtonLoaderTarget.classList.add('d-none');
        this.itemPriceTarget.innerHTML = Number(this.itemPriceTarget.innerHTML) + this.priceValue;
        let cartBadge = document.getElementById('cartBadge');
        cartBadge.innerText = +cartBadge.innerText + 1;

        let totalPrice = document.getElementById('totalPrice').innerHTML;
        document.getElementById('totalPrice').innerHTML = Number(totalPrice) + Number(this.priceValue);

        let deliveryCharge;

        const shippingMethodSelect = document.getElementById("checkout_shippingMethod");
        const selectedOptionText = shippingMethodSelect.options[shippingMethodSelect.selectedIndex].text;
        const match = selectedOptionText.match(/\d+/);

        if (match && match[0]) {
            deliveryCharge = parseInt(match[0]);
        }

        document.getElementById('deliveryCharge').innerHTML = Number(deliveryCharge);
        document.getElementById('grandTotal').innerHTML = Number(totalPrice) + Number(this.priceValue) + Number(deliveryCharge);
    }

    async subtract() {
        if (this.quantityTarget.value > 1) {
            this.quantityTarget.value--;
            const apiUrl = `/cart_update/substract/${this.idValue}`;
            this.subtractButtonTarget.classList.add('d-none');
            this.subtractButtonLoaderTarget.classList.remove('d-none');
            const response = await fetch(apiUrl);
            this.subtractButtonTarget.classList.remove('d-none');
            this.subtractButtonLoaderTarget.classList.add('d-none');
            this.itemPriceTarget.innerHTML = Number(this.itemPriceTarget.innerHTML) - this.priceValue;
            let cartBadge = document.getElementById('cartBadge');
            cartBadge.innerText = +cartBadge.innerText - 1;

            let totalPrice = document.getElementById('totalPrice').innerHTML;
            document.getElementById('totalPrice').innerHTML = Number(totalPrice) - Number(this.priceValue);

            let deliveryCharge;

            const shippingMethodSelect = document.getElementById("checkout_shippingMethod");
            const selectedOptionText = shippingMethodSelect.options[shippingMethodSelect.selectedIndex].text;
            const match = selectedOptionText.match(/\d+/);

            if (match && match[0]) {
                deliveryCharge = parseInt(match[0]);
            }

            document.getElementById('deliveryCharge').innerHTML = Number(deliveryCharge);
            document.getElementById('grandTotal').innerHTML = Number(totalPrice) - Number(this.priceValue) + Number(deliveryCharge);
        }
    }
}