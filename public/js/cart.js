document.addEventListener('DOMContentLoaded', loadCart);

// Utility function to clear error messages
function clearErrors() {
    const errorElements = ['name-error', 'email-error', 'phone-error', 'city-error', 'address-error'];
    errorElements.forEach(id => document.getElementById(id).innerText = '');
}

// Utility function to display error messages
function displayError(id, message) {
    document.getElementById(id).innerText = message;
}

// Utility function to update the cart total
function updateCartTotal(subtotal) {
    const formattedTotal = subtotal.toFixed(2) + " MAD";
    if(document.getElementById('cart-total')){
        document.getElementById('cart-total').innerText  = `Total ${formattedTotal}`;
    }
    document.querySelector('.total-price').innerHTML = formattedTotal;
    localStorage.setItem('total_price', subtotal);
}

// Load cart from localStorage and render it
function loadCart() {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    const cartProductContainer = document.getElementById('cart-product');
    cartProductContainer.innerHTML = '';
    document.querySelector('.header-cart-count').innerHTML = Object.keys(cart).length ?? 0;
    const subtotal = Object.values(cart).reduce((acc, item) => {
        const itemSubtotal = parseFloat(item.price) * item.quantity;
        acc += itemSubtotal;
        cartProductContainer.innerHTML += createCartRow(item, itemSubtotal);
        return acc;
    }, 0);

    updateCartTotal(subtotal);
}

// Create a row for the cart item
function createCartRow(item, itemSubtotal) {
    return `
            <tr>
                <td class="product-name">${item.name}</td>
                <td class="product-price">${parseFloat(item.price).toFixed(2)} MAD</td>
                <td class="product-quantity">
                    <input class='input-quantity-cart' type="number" value="${item.quantity}" min="1" onchange="updateQuantity('${item.id}', this.value)">
                </td>
                <td class="product-subtotal">${itemSubtotal.toFixed(2)} MAD</td>
                <td class="product-action">
                    <button onclick="removeFromCart('${item.id}')">Remove</button>
                </td>
            </tr>
        `;
}

// Update the quantity of a cart item
function updateQuantity(productId, newQuantity) {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    if (newQuantity < 1) return; // Prevent zero or negative quantities
    cart[productId].quantity = parseInt(newQuantity, 10);
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart(); // Refresh the cart display
}

// Remove an item from the cart
function removeFromCart(productId) {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    delete cart[productId];
    localStorage.setItem('cart', JSON.stringify(cart));
    loadCart(); // Refresh the cart display

    // Check if the cart is empty
    if (Object.keys(cart).length === 0) {
        updateSessionCart(cart);
        window.location.href = '/'; // Navigate to the homepage
    }
}

// Update the cart session on the server
function updateSessionCart(cart) {
    fetch('/update-cart-session', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(cart)
    })
        .then(response => {
            console.log('Session updated');
        })
        .catch(error => console.error('Error updating session:'));
}

// Handle order submission
function submitOrder(event) {
    event.preventDefault();
    clearErrors();

    const formData = getFormData();
    if (!validateForm(formData)) return;

    fetch('/order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(formData),
    })
        .then(handleOrderResponse)
        .catch(error => console.error('Error:', error));
}

// Collect form data
function getFormData() {
    return {
        name: document.getElementById('name').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        city: document.getElementById('city').value,
        address: document.getElementById('address').value,
        cartItems: getCartItems()
    };
}

// Validate form data
function validateForm(data) {
    let hasError = false;
    if (!data.name) { displayError('name-error', 'Name is required.'); hasError = true; }
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (data.email && !emailPattern.test(data.email)) { displayError('email-error', 'Please enter a valid email address.'); hasError = true; }
    const phonePattern = /^(06|05|07)\d{8}$/;
    if (!phonePattern.test(data.phone)) { displayError('phone-error', 'Phone number must start with 06, 05, or 07 and be exactly 10 digits long.'); hasError = true; }
    if (!data.city) { displayError('city-error', 'City is required.'); hasError = true; }
    if (!data.address) { displayError('address-error', 'Address is required.'); hasError = true; }
    return !hasError;
}

// Handle the response after submitting the order
function handleOrderResponse(response) {
    const orderResultDiv = document.getElementById('order-result');
    const isFailed = response.status !== 201; // Consider anything that's not 201 as a failure
    orderResultDiv.className = isFailed ? 'order-result-failed' : 'order-result';

    if (response.status === 429) {
        showOrderResult('Too Many Requests, Try later!');
    } else if (response.status === 403) {
        showOrderResult('You have a pending order!');
    } else if (response.status === 201) {
        showOrderResult('Your order has been received, and our team will call you shortly to confirm. Expect our call between 11:00 AM and 2:00 PM.<br/>Thank you for choosing us!');
        localStorage.removeItem('cart');
        localStorage.removeItem('total_price');
        updateSessionCart({});
        document.querySelector('.cart-page-wrapper').remove();
        updateCartTotal(0) ;
        document.querySelector('.header-cart-count').innerHTML = "0";
    } else {
        showOrderResult(`Error: ${response.message || 'An unexpected error occurred.'}`);
    }
}

// Show the order result message
function showOrderResult(message) {
    const orderResultDiv = document.getElementById('order-result');
    orderResultDiv.innerHTML = `
            <button class="close-btn" onclick="closeOrderResult()">×</button>
            <p>${message}</p>
        `;
    orderResultDiv.style.display = 'block';
}

// Function to get cart items from local storage
function getCartItems() {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    return Object.values(cart).map(item => ({
        id: item.id,
        quantity: item.quantity
    }));
}

// Close the order result message
function closeOrderResult() {
    const cart = JSON.parse(localStorage.getItem('cart')) || {};
    document.getElementById('order-result').style.display = 'none';

    if (Object.keys(cart).length === 0) {
        window.location.href = '/';
    }
}
