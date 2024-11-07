@extends('Layouts.user')
@section('content')

    <!-- Products Section -->
    <main>
        <div class="loader" id="loader" style="display: none;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200">
                <circle fill="#1988FF" stroke="#1988FF" stroke-width="15" r="15" cx="40" cy="65">
                    <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.4"></animate>
                </circle>
                <circle fill="#1988FF" stroke="#1988FF" stroke-width="15" r="15" cx="100" cy="65">
                    <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="-.2"></animate>
                </circle>
                <circle fill="#1988FF" stroke="#1988FF" stroke-width="15" r="15" cx="160" cy="65">
                    <animate attributeName="cy" calcMode="spline" dur="2" values="65;135;65;" keySplines=".5 0 .5 1;.5 0 .5 1" repeatCount="indefinite" begin="0"></animate>
                </circle>
            </svg>
        </div>

        <div class="filter-bar">
            <div class="filter-item">
                <select id="categories">
                    <option value="all">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <select id="order">
                    <option value="low-to-high">Price: Low to High</option>
                    <option value="high-to-low">Price: High to Low</option>
                </select>
            </div>

            <div class="filter-item">
                <input type="text" id="search" placeholder="Search for product..." />
            </div>
        </div>

        <section class="products">
            <div class="container">
                <div class="category-card">
                    <div class="product-grid" id="productGrid">
                        <!-- Products will be rendered here -->
                    </div>
                </div>
            </div>
        </section>
    </main>

    <div class="modal" id="productModal" style="display: none">
        <div class="modal-content">
            <span class="close-product-modal" id="closeModal">X</span>
            <!-- Product Title Section with Border -->
            <div class="product-title-section">
                <h2 id="modalProductName" class="product-title"></h2>
            </div>

            <!-- Main Content: Left Side Image & Right Side Information -->
            <div class="content-wrapper">
                <!-- Left Side: Main Image Display -->
                <div class="modal-left">
                    <img id="modalProductImage" src="" alt="Product Image" class="modal-image" />
                    <div id="imageButtonsContainer" class="image-buttons"></div>
                </div>

                <!-- Right Side: Product Information -->
                <div class="modal-right">
                    <!-- Description Section (conditionally displayed) -->
                    <div id="descriptionSection" class="section">
                        <h3 class="section-heading">Description</h3>
                        <p id="modalProductDescription" class="product-description"></p>
                    </div>

                    <!-- Price Section -->
                    <div class="section">
                        <h3 class="section-heading">Price</h3>
                        <div class="product-prices">
                            <strong class="new-price" id="modalProductPrice"></strong>
                            <span class="old-price" id="modalProductOldPrice"></span>
                        </div>
                    </div>

                    <!-- Characteristics Section -->
                    <div class="section">
                        <h3 class="section-heading">Characteristics</h3>
                        <div id="modalProductCharacteristics" class="product-characteristics"></div>
                    </div>

                    <!-- Quantity & Add to Cart Button -->
                    <div class="quantity-cart-container">
                        <input type="number" min="1" value="1" class="product-quantity" id="modalProductQuantity" />
                        <button class="add-to-cart-btn" id="modalAddToCartBtn">Add to Cart</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{asset('/js/index.js')}}"></script>

@endsection
