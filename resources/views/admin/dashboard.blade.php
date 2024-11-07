@extends('Layouts.admin')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Total Revenue Card -->
            <div class="col-sm-6 col-xxl-4 col-lg-6">
                <div class="main-tiles border-5 border-0 card-hover card o-hidden">
                    <div class="custome-1-bg b-r-4 card-body">
                        <div class="media align-items-center static-top-widget">
                            <div class="media-body p-0">
                                <span class="m-0">Total Revenue</span>
                                <h4 class="mb-0 counter" id="total-revenue">0 MAD</h4>
                            </div>
                            <div class="align-self-center text-center">
                                <i class="ri-database-2-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Orders Card -->
            <div class="col-sm-6 col-xxl-4 col-lg-6">
                <div class="main-tiles border-5 card-hover border-0 card o-hidden">
                    <div class="custome-2-bg b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="media-body p-0">
                                <span class="m-0">Total Orders</span>
                                <h4 class="mb-0 counter" id="total-orders">0</h4>
                            </div>
                            <div class="align-self-center text-center">
                                <i class="ri-shopping-bag-3-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Products Card -->
            <div class="col-sm-6 col-xxl-4 col-lg-6">
                <div class="main-tiles border-5 card-hover border-0 card o-hidden">
                    <div class="custome-3-bg b-r-4 card-body">
                        <div class="media static-top-widget">
                            <div class="media-body p-0">
                                <span class="m-0">Total Products</span>
                                <h4 class="mb-0 counter" id="total-products">0
                                    <a href="{{ route('new-product') }}" class="badge badge-light-secondary grow">ADD NEW</a>
                                </h4>
                            </div>
                            <div class="align-self-center text-center">
                                <i class="ri-chat-3-line"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Best Selling Products Section -->
            <div class="col-xl-6 col-md-12">
                <div class="card o-hidden card-hover">
                    <div class="card-header card-header-top card-header--2 px-0 pt-0">
                        <div class="card-header-title">
                            <h4>Best Selling Products</h4>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="best-selling-table w-image table border-0" id="best-selling-products">
                                <tbody>
                                <!-- Best selling products will be appended here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Section -->
            <div class="col-xl-6">
                <div class="card o-hidden card-hover">
                    <div class="card-header card-header-top card-header--2 px-0 pt-0">
                        <div class="card-header-title">
                            <h4>Recent Orders</h4>
                        </div>
                    </div>
                    <div class="card-body p-1">
                        <div>
                            <div class="table-responsive">
                                <table class="best-selling-table table border-0" id="recent-orders">
                                    <tbody>
                                    <!-- Recent orders will be appended here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Fetch data from the server
        async function loadDashboardData() {
            try {
                const response = await fetch('{{ route('load-data-dashboard') }}');
                const data = await response.json();

                // Update Total Revenue, Total Orders, and Total Products
                document.getElementById('total-revenue').innerText = `${data.total_price_sum} MAD`;
                document.getElementById('total-orders').innerText = data.count_orders;
                document.getElementById('total-products').innerText = data.product_count;

                // Best Selling Products
                const bestSellingTable = document.getElementById('best-selling-products').querySelector('tbody');
                bestSellingTable.innerHTML = ''; // Clear existing products
                data.best_selling_products.forEach(product => {
                    const productHTML = `
                <tr>
                    <td>
                        <div class="best-product-box">
                            <div class="product-name">
                                <h5>${product.name}</h5>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="product-detail-box">
                            <h6>Price</h6>
                            <h5>${product.price} MAD</h5>
                        </div>
                    </td>
                    <td>
                        <div class="product-detail-box">
                            <h6>Orders</h6>
                            <h5>${product.orders}</h5>
                        </div>
                    </td>
                </tr>
            `;
                    bestSellingTable.innerHTML += productHTML;
                });

                // Recent Orders
                const recentOrdersTable = document.getElementById('recent-orders').querySelector('tbody');
                recentOrdersTable.innerHTML = ''; // Clear existing orders
                data.latest_order.forEach(order => {
                    const orderHTML = `
                <tr>
                    <td>
                        <div class="best-product-box">
                            <div class="product-name">
                                <h5>${order.name}</h5>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="product-detail-box">
                            <h6>City</h6>
                            <h5>${order.city}</h5>
                        </div>
                    </td>
                    <td>
                        <div class="product-detail-box">
                            <h6>Total Price</h6>
                            <h5>${order.total_price} MAD</h5>
                        </div>
                    </td>
                    <td>
                        <div class="product-detail-box">
                            <h6>Status</h6>
                            <h5>${order.status}</h5>
                        </div>
                    </td>
                    <td>
                        <div class="product-detail-box">
                            <h6>Payment Status</h6>
                            <h5>${order.payment_status}</h5>
                        </div>
                    </td>
                </tr>
            `;
                    recentOrdersTable.innerHTML += orderHTML;
                });
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        // Load data on page load
        document.addEventListener('DOMContentLoaded', loadDashboardData);
    </script>

@endsection
