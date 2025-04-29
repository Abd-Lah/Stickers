window.APP_URL = "{{ config('app.url') }}";
    document.addEventListener('DOMContentLoaded', () => {
        const productGrid = document.getElementById('productGrid');
        const loader = document.getElementById('loader');
        const categoriesSelect = document.getElementById('categories');
        const orderSelect = document.getElementById('order');
        const searchInput = document.getElementById('search');
        let products = []; // Store fetched products

        const fetchProducts = async () => {
            loader.style.display = 'block'; // Show the loader
            const categoryId = categoriesSelect.value;
            const order = orderSelect.value;
            const search = searchInput.value;

            try {
                const footer = document.querySelector('.footer');
                const response = await fetch(`/api/products?category_id=${categoryId}&order=${order}&search=${search}`);
                products = await response.json(); // Store the products in the global variable
                // Check if the number of products is not more than 5 to fix the footer position
                footer.style.position = products.length <= 5 ? 'fixed' : 'relative';
                renderProducts(products);
            } catch (error) {
                console.error('Error fetching products:', error);
            } finally {
                loader.style.display = 'none'; // Hide the loader
            }
        };

    const renderProducts = (products) => {
        productGrid.innerHTML = ''; // Clear existing products
        if (Object.keys(products).length !== 0) {
            products.forEach(product => {
            const price = product.price || 0; // Default to 0 if undefined
            const discount = product.discount || 0; // Default to 0 if undefined
            const oldPrice = (price * (1 + discount / 100)).toFixed(2); // Calculate old price safely

            const div = document.createElement('div');
            div.className = 'product-item';
            div.innerHTML = `
                            <div class="product-image">
                                <a href="#">
                                    <img src="/storage/stickers-image/${product.image[0]}" alt="${product.name}" class="img1" />
                                    <img src="/storage/stickers-image/${product.image[1] || product.image[0]}" alt="${product.name}" class="img2" />
                                </a>
                            </div>
                            <div class="product-info">
                                <h3 class="product-title">${product.name}</h3>
                                <div class="product-prices">
                                    <strong class="new-price">${price} MAD</strong>
                                </div>
                                <span class="product-discount">-${discount}%</span>
                                <button class="view-details-btn" data-id="${product.id}">View Details</button> <!-- Added button -->
                            </div>
                        `;
        productGrid.appendChild(div);
    });

        attachAddToCartListeners();
        attachViewDetailsListeners(); // Attach listeners for detail view
    } else {
        productGrid.innerHTML = `
                         <div class="product-not-found-container">
                            <div class="product-not-found">
                                <h1>Product Not Found</h1>
                                <p>We're sorry, but the product you are looking for doesn't exist .</p>
                            </div>
                        </div>
                        `
    }
    };

    const attachAddToCartListeners = () => {
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent any default action
    const productId = button.dataset.id;
    addToCart(productId);
});
});
};

    const attachViewDetailsListeners = () => {
    document.querySelectorAll('.view-details-btn').forEach(button => {
    button.addEventListener('click', (event) => {
    event.preventDefault(); // Prevent default action
    const productId = button.dataset.id;
    const product = products.find(p => p.id == productId); // Find product in array
    if (product) {
    openModal(product);
}
});
});
};

    const openModal = (product) => {
    document.getElementById('modalProductName').innerText = product.name;

    // Display main image
    const mainImage = document.getElementById('modalProductImage');
    mainImage.src = `/storage/stickers-image/${product.image[0]}`;

    // Additional images as buttons
    const imageButtonsContainer = document.getElementById('imageButtonsContainer');
    imageButtonsContainer.innerHTML = '';
    product.image.forEach(img => {
    const imgButton = document.createElement('button');
    imgButton.style.backgroundImage = `url('/storage/stickers-image/${img}')`;
    imgButton.className = 'image-button';
    imgButton.onclick = () => { mainImage.src = `/storage/stickers-image/${img}`; };
    imageButtonsContainer.appendChild(imgButton);
});

    // Conditionally display description
    const descriptionSection = document.getElementById('descriptionSection');
    if (product.description) {
    document.getElementById('modalProductDescription').innerText = product.description;
    descriptionSection.style.display = 'block';
} else {
    descriptionSection.style.display = 'none';
}

    // Price and discount
    document.getElementById('modalProductPrice').innerText = `${product.price} MAD`;
    if (product.discount) {
    const oldPrice = (product.price * (1 + product.discount / 100)).toFixed(2);
    document.getElementById('modalProductOldPrice').innerText = `${oldPrice} MAD`;
    document.getElementById('modalProductOldPrice').style.display = 'inline';
} else {
    document.getElementById('modalProductOldPrice').style.display = 'none';
}

    // Characteristics
    const characteristicsContainer = document.getElementById('modalProductCharacteristics');
    characteristicsContainer.innerHTML = '';
    const ul = document.createElement('ul');
    product.caracteristics.forEach(char => {
    const li = document.createElement('li');
    li.innerText = char;
    ul.appendChild(li);
});
    characteristicsContainer.appendChild(ul);

    // Show modal
    document.getElementById('productModal').style.display = 'flex';
};




    const closeModal = () => {
    document.getElementById('productModal').style.display = 'none'; // Hide the modal
};

    // Attach event listener for close button
    document.getElementById('closeModal').addEventListener('click', closeModal);

    // Add to cart from modal
    document.getElementById('modalAddToCartBtn').addEventListener('click', () => {
    const productId = products.find(p => p.name === document.getElementById('modalProductName').innerText).id;
    const quantityInput = document.getElementById('modalProductQuantity');
    const quantity = parseInt(quantityInput.value, 10);
    addToCart(productId, quantity);
});
    function updateCartSession(cart) {
        fetch('/update-cart-session', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ cart: cart })
        })
            .then(response => response.json())
            .then(data => {
                console.log('Session updated:', data);
            })
            .catch(error => {
                console.error('Error updating session:', error);
            });
    }
    const addToCart = (productId, quantity = 1) => {
        // Ensure total_price is initialized as a number
        let total_price = parseFloat(localStorage.getItem('total_price')) || 0;

        if (quantity <= 0) return; // Avoid adding zero or negative quantities

        const product = products.find(p => p.id == productId); // Find product in array

        if (!product) return; // Product not found

        let cart = JSON.parse(localStorage.getItem('cart')) || {};

        const cartItem = {
        id: product.id,
        name: product.name,
        price: product.price,
        discount: product.discount,
        quantity: quantity,
    };

        if (cart[productId]) {
        cart[productId].quantity += quantity; // Increment quantity
    } else {
        cart[productId] = cartItem; // Add new product
    }

    localStorage.setItem('cart', JSON.stringify(cart));

    // Update total price with the new product
    total_price += product.price * quantity; // Add product price times quantity
    total_price = parseFloat(total_price).toFixed(2); // Ensure total_price is a number with two decimals

    localStorage.setItem('total_price', total_price);

    // Update total products count in the cart
    const totalProducts = Object.keys(cart).length;
    document.querySelector('.header-cart-count').innerHTML = totalProducts;
    // Update displayed total price
    document.querySelector('.total-price').innerHTML = total_price + " MAD"; // Update displayed total price

    updateCartSession(cart);
    closeModal();
};

    // Event listeners for dynamic filtering
    categoriesSelect.addEventListener('change', fetchProducts);
    orderSelect.addEventListener('change', fetchProducts);
    searchInput.addEventListener('input', fetchProducts);

    // Initial load of products
    fetchProducts();
    updateCartSession(cart);

});
