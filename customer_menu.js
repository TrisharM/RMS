document.addEventListener('DOMContentLoaded', function () {
    // Fetch menu items from the database
    fetch('menu.php')
        .then(response => response.json())
        .then(data => {
            const menuItemsContainer = document.getElementById('menu-items');
            data.forEach(item => {
                menuItemsContainer.innerHTML += `
                    <div class="menu-item">
                        <h3>${item.name}</h3>
                        <p>${item.description}</p>
                        <p>Price: $${item.price}</p>
                        <button class="add-to-cart" data-id="${item.id}" data-name="${item.name}" data-price="${item.price}">Add to Cart</button>
                    </div>
                `;
            });

            // Add to cart functionality
            const addToCartButtons = document.querySelectorAll('.add-to-cart');
            addToCartButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.getAttribute('data-id');
                    const name = button.getAttribute('data-name');
                    const price = button.getAttribute('data-price');
                    addToCart(id, name, price);
                });
            });
        });

    // Cart data
    let cart = [];

    // Add item to cart
    function addToCart(id, name, price) {
        const existingItem = cart.find(item => item.id === id);
        if (existingItem) {
            existingItem.quantity += 1;
        } else {
            cart.push({ id, name, price, quantity: 1 });
        }
        displayCart();
    }

    // Display cart
    function displayCart() {
        const cartContainer = document.querySelector('.navbar .cart-container');
        cartContainer.innerHTML = '';
        cart.forEach(item => {
            cartContainer.innerHTML += `
                <div class="cart-item">
                    <p>${item.name}</p>
                    <p>Quantity: ${item.quantity}</p>
                    <p>Total: $${(item.quantity * item.price).toFixed(2)}</p>
                    <button class="increase-quantity" data-id="${item.id}">+</button>
                    <button class="decrease-quantity" data-id="${item.id}">-</button>
                    <button class="remove-item" data-id="${item.id}">Remove</button>
                </div>
            `;
        });

        // Attach events for cart item buttons
        document.querySelectorAll('.increase-quantity').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const item = cart.find(i => i.id === id);
                item.quantity += 1;
                displayCart();
            });
        });

        document.querySelectorAll('.decrease-quantity').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                const item = cart.find(i => i.id === id);
                if (item.quantity > 1) {
                    item.quantity -= 1;
                } else {
                    cart = cart.filter(i => i.id !== id);
                }
                displayCart();
            });
        });

        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                cart = cart.filter(i => i.id !== id);
                displayCart();
            });
        });
    }
});
