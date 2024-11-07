@extends('Layouts.user')
@section('content')

    <main>
        <section class="cart-page">
            <div class="container">
                <div id="order-result" style="display: none;" class="order-result">
                    <button class="close-btn" onclick="closeOrderResult()">×</button>
                    <p>Order submitted successfully!</p>
                </div>
                <div class="cart-page-wrapper">
                    <div class="shop-table-wrapper">

                        <table class="shop-table">
                            <thead>
                            <tr>
                                <th class="product-name">Product</th>
                                <th class="product-price">Price</th>
                                <th class="product-quantity">Quantity</th>
                                <th class="product-subtotal">Subtotal</th>
                                <th class="product-action">Action</th>
                            </tr>
                            </thead>
                            <tbody class="cart-wrapper" id="cart-product">
                            <!-- Cart items will be dynamically loaded here -->
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-collaterals">
                        <div class="cart-totals">
                            <strong id="cart-total">0.00 MAD</strong>
                        </div>

                        <form id="order-form" onsubmit="submitOrder(event)">
                            <input type="text" id="name" placeholder="Your Name" required>
                            <span id="name-error" class="error-message"></span>

                            <input type="email" id="email" placeholder="Your Email (optional)">
                            <span id="email-error" class="error-message"></span>

                            <input type="text" id="phone" placeholder="Your Phone" required>
                            <span id="phone-error" class="error-message"></span>

                            <input type="text" id="city" placeholder="Your City" required>
                            <span id="city-error" class="error-message"></span>

                            <input type="text" id="address" placeholder="Your Address" required>
                            <span id="address-error" class="error-message"></span>

                            <button type="submit">Order Now</button>
                        </form>

                    </div>
                </div>
            </div>
        </section>
    </main>



    <script src="{{asset('/js/cart.js')}}"></script>



@endsection
