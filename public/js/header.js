// Update the displayed total price from localStorage
const totalPrice = parseFloat(localStorage.getItem('total_price')) || 0;
document.querySelector('.total-price').innerHTML = totalPrice.toFixed(2) + " MAD";

// Update the displayed cart count from localStorage
let cart = JSON.parse(localStorage.getItem('cart')) || {};
let totalQuantity = Object.keys(cart).length ?? 0;
document.querySelector('.header-cart-count').innerHTML = totalQuantity;

document.querySelector('.header-cart-link').addEventListener('click', () => {
    cart = JSON.parse(localStorage.getItem('cart')) || {};
    totalQuantity = Object.keys(cart).length ?? 0;
    if (totalQuantity > 0) {
        window.location.href = "/cart"; // Use double quotes to wrap PHP code
    } else {
        alert('Cart Empty!');
    }
});
