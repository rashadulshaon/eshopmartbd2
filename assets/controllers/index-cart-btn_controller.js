import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static values = {
        infoUrl: String
    };

    async addToCart() {
        try {
            await fetch(this.infoUrlValue);
            let cartBadge = document.getElementById('cartBadge');
            cartBadge.innerText = +cartBadge.innerText + 1;
        } catch (e) {
            console.log(e.message);
        }
    }
} 