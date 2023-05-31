import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['quantity', 'addedToCartMessage', 'productId']

    connect() {
        console.log('Connected');
    }

    subtraction() {
        if (this.quantityTarget.value > 1) {
            this.quantityTarget.value--;
        }
    }

    addition() {
        this.quantityTarget.value++;
    }

    async addToCart(event) {
        event.preventDefault();
        const quantity = this.quantityTarget.value;
        const productId = this.productIdTarget.value;
        const apiUrl = `/cart_update/add/${productId}/${quantity}`;
        await fetch(apiUrl);
        let cartBadge = document.getElementById('cartBadge');
        cartBadge.innerText = Number(cartBadge.innerText) + Number(quantity);
        this.addedToCartMessageTarget.classList.toggle('d-none');
    }

    directCheckout() {
        location.replace(`/checkout/${this.productIdTarget.value}/${this.quantityTarget.value}`);
    }
}