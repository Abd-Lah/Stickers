@extends('Layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="status-filter" class="form-label">Filter by Status:</label>
                                    <select id="status-filter" class="form-select" aria-label="Filter by Status">
                                        <option value="all">All</option>
                                        <option value="confirmed">Confirmed</option>
                                        <option value="pending">Pending</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label for="date-from" class="form-label">Date From:</label>
                                    <input type="date" id="date-from" class="form-control" />
                                </div>

                                <div class="col-md-4">
                                    <label for="date-to" class="form-label">Date To:</label>
                                    <input type="date" id="date-to" class="form-control" />
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table all-package theme-table table-product" id="orders-table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Total Price</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody id="orders-body">
                                <!-- Orders will be populated here by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center py-4 px-3" id="pagination-controls">
            <!-- Pagination will be populated here by JavaScript -->
        </div>

        <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content rounded-3 shadow-lg">
                    <div class="modal-header border-0">
                        <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div id="order-details">
                            <!-- Order Information with rounded borders -->
                            <div class="row mb-3 rounded-3 border p-3 shadow-sm">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> <span id="order-name" class="fw-semibold text-muted"></span></p>
                                    <p><strong>Email:</strong> <span id="order-email" class="fw-semibold text-muted"></span></p>
                                    <p><strong>Phone:</strong> <span id="order-phone" class="fw-semibold text-muted"></span></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Address:</strong> <span id="order-address" class="fw-semibold text-muted"></span></p>
                                    <p><strong>City:</strong> <span id="order-city" class="fw-semibold text-muted"></span></p>
                                    <p><strong>Status:</strong> <span id="order-status" class="fw-semibold text-muted"></span></p>
                                </div>
                            </div>

                            <p><strong>Payment Method:</strong> <span id="order-payment-method" class="fw-semibold text-muted"></span></p>
                            <p><strong>Payment Status:</strong> <span id="order-payment-status" class="fw-semibold text-muted"></span></p>
                            <p><strong>Total Price:</strong> <span id="order-total-price" class="fw-semibold text-muted"></span> MAD</p>

                            <!-- Order Items Table with rounded border -->
                            <table class="table table-striped table-bordered table-hover mt-4 rounded-3 shadow-sm">
                                <thead class="table-light">
                                <tr>
                                    <th class="fs-6 fw-semibold">Sticker Name</th>
                                    <th class="fs-6 fw-semibold">Quantity</th>
                                    <th class="fs-6 fw-semibold">Sub Price</th>
                                </tr>
                                </thead>
                                <tbody id="order-items">
                                <!-- Order items will be dynamically loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <!-- Payment status button will be shown conditionally -->
                        <button type="button" class="btn btn-primary" id="toggle-payment-status" style="display: none;">Mark as Paid</button>
                    </div>
                </div>
            </div>
        </div>




        @include('admin.includes.modal') <!-- Ensure this modal includes the necessary HTML -->
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ordersBody = document.getElementById('orders-body');
            const paginationControls = document.getElementById('pagination-controls');
            const deleteForm = document.getElementById('delete-form');
            const deleteMessage = document.getElementById('delete-message');
            const statusFilter = document.getElementById('status-filter');
            const dateFrom = document.getElementById('date-from');
            const dateTo = document.getElementById('date-to');

            // Fetch orders based on the current page, selected status, and date range
            function fetchOrders(page = 1, status = 'all', from = '', to = '') {
                fetch(`orders/load?page=${page}&status=${status}&from=${from}&to=${to}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        renderOrders(data.orders);
                        renderPagination(data.pagination);
                    })
                    .catch(error => console.error('Error fetching orders:', error));
            }

            function fetchOrderDetails(orderId) {
                fetch(`/admin/orders/${orderId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        const order = data.order;

                        // Populate order details
                        document.getElementById('order-name').innerText = order.name;
                        document.getElementById('order-email').innerText = order.email;
                        document.getElementById('order-phone').innerText = order.phone;
                        document.getElementById('order-address').innerText = order.address;
                        document.getElementById('order-city').innerText = order.city;
                        document.getElementById('order-status').innerText = order.status;
                        document.getElementById('order-payment-method').innerText = order.payment_method;
                        document.getElementById('order-payment-status').innerText = order.payment_status;
                        document.getElementById('order-total-price').innerText = `${order.total_price}`;

                        // Set button based on payment status
                        const togglePaymentStatusButton = document.getElementById('toggle-payment-status');
                        togglePaymentStatusButton.style.display = 'none';  // Hide button initially

                        if (order.payment_status === 'pending') {
                            togglePaymentStatusButton.style.display = 'inline-block';
                            togglePaymentStatusButton.innerText = 'Mark as Paid';
                            togglePaymentStatusButton.onclick = () => updatePaymentStatus(orderId, 'done');
                        } else if (order.payment_status === 'done') {
                            togglePaymentStatusButton.style.display = 'inline-block';
                            togglePaymentStatusButton.innerText = 'Mark as Unpaid';
                            togglePaymentStatusButton.onclick = () => updatePaymentStatus(orderId, 'pending');
                        }

                        // Populate order items
                        const orderItemsTable = document.getElementById('order-items');
                        orderItemsTable.innerHTML = ''; // Clear the table

                        if (data.orderItems && data.orderItems.length > 0) {
                            data.orderItems.forEach(item => {
                                const row = `
                        <tr>
                            <td>${item.sticker_name}</td>
                            <td>${item.quantity}</td>
                            <td>${item.sub_price} MAD</td>
                        </tr>
                    `;
                                orderItemsTable.insertAdjacentHTML('beforeend', row);
                            });
                        } else {
                            orderItemsTable.innerHTML = '<tr><td colspan="3">No items found</td></tr>';
                        }

                        // Show modal
                        $('#orderDetailsModal').modal('show');
                    })
                    .catch(error => console.error('Error fetching order details:', error));
            }

            function updatePaymentStatus(orderId, newStatus) {
                fetch(`/admin/orders/payment/${orderId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to update payment status');
                        }
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message);
                        $('#orderDetailsModal').modal('hide');
                    })
                    .catch(error => console.error('Error updating payment status:', error));
            }




            function renderOrders(orders) {
                ordersBody.innerHTML = '';
                orders.forEach(order => {
                    const row = `
                <tr>
                    <td>${order.name}</td>
                    <td>${order.phone}</td>
                    <td>${order.address}</td>
                    <td>${order.city}</td>
                    <td>${order.total_price} MAD</td>
                    <td>
                        <ul>
                            <li>
                                <a href="javascript:void(0)" class="order-details-button" data-order-id="${order.id}">
                                    <i class="ri-eye-line"></i>
                                </a>
                            </li>
                            ${order.status !== 'confirmed' ? `
                            <li>
                                <a href="javascript:void(0)" class="confirm-button" data-confirm-url="/admin/orders/confirm/${order.id}" data-order-name="${order.name}">
                                    <i class="ri-check-line"></i>
                                </a>
                            </li>
                            ` : ''}
                            <li>
                                <a href="javascript:void(0)" class="delete-button" data-delete-url="/admin/orders/delete/${order.id}" data-order-name="${order.name}">
                                    <i class="ri-delete-bin-line"></i>
                                </a>
                            </li>
                        </ul>
                    </td>
                </tr>
            `;
                    ordersBody.innerHTML += row;
                });

                addOrderDetailsEventListeners();
                addEventListeners();
            }
            function addPaginationEventListeners() {
                document.querySelectorAll('.pagination .page-link').forEach(link => {
                    link.addEventListener('click', function(event) {
                        event.preventDefault(); // Prevent the default behavior (page reload)

                        // Get the page number from the link's data-page attribute
                        const page = this.getAttribute('data-page');

                        // If the page link is valid, fetch orders for that page
                        if (page && page !== '#') {
                            fetchOrders(page, statusFilter.value, dateFrom.value, dateTo.value);
                        }
                    });
                });
            }

            function renderPagination(pagination) {
                paginationControls.innerHTML = '';
                if (pagination && pagination.total > 0) {
                    paginationControls.innerHTML = `
                <div class="text-sm text-secondary">
                    Showing ${pagination.from} to ${pagination.to} of ${pagination.total} orders
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <li class="page-item ${pagination.current_page === 1 ? 'disabled' : ''}">
                            <a class="page-link" href="javascript:void(0)" data-page="${pagination.prev_page_url || '#'}" aria-disabled="${pagination.current_page === 1}">
                                Previous
                            </a>
                        </li>
                        ${Array.from({ length: pagination.last_page }, (_, i) => i + 1).map(page => `
                            <li class="page-item ${page === pagination.current_page ? 'active' : ''}">
                                <a class="page-link" href="javascript:void(0)" data-page="${page}" ${pagination.current_page === page ? 'aria-disabled="true"' : ''}>
                                    ${page}
                                </a>
                            </li>
                        `).join('')}
                        <li class="page-item ${pagination.current_page === pagination.last_page ? 'disabled' : ''}">
                            <a class="page-link" href="javascript:void(0)" data-page="${pagination.next_page_url || '#'}" aria-disabled="${pagination.current_page === pagination.last_page}">
                                Next
                            </a>
                        </li>
                    </ul>
                </nav>
            `;
                    addPaginationEventListeners();
                } else {
                    paginationControls.innerHTML = `<div class="text-sm text-secondary">No orders found.</div>`;
                }
            }

            function addOrderDetailsEventListeners() {
                document.querySelectorAll('.order-details-button').forEach(button => {
                    button.addEventListener('click', function () {
                        const orderId = this.getAttribute('data-order-id');
                        fetchOrderDetails(orderId);
                    });
                });
            }

            function addEventListeners() {
                document.querySelectorAll('.confirm-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const confirmUrl = this.getAttribute('data-confirm-url');
                        const orderName = this.getAttribute('data-order-name');

                        fetch(confirmUrl, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Network response was not ok');
                                }
                                return response.json();
                            })
                            .then(data => {
                                fetchOrders(1, statusFilter.value, dateFrom.value, dateTo.value);
                            })
                            .catch(error => console.error('Error confirming order:', error));
                    });
                });

                document.querySelectorAll('.delete-button').forEach(button => {
                    button.addEventListener('click', function() {
                        const deleteUrl = this.getAttribute('data-delete-url');
                        const orderName = this.getAttribute('data-order-name');

                        // Set delete URL and message in modal
                        deleteForm.setAttribute('action', deleteUrl);
                        deleteMessage.innerText = `Are you sure you want to delete the "${orderName}" order?`;

                        // Trigger the modal
                        $('#confirmationModal').modal('show');
                    });
                });
            }

            deleteForm.addEventListener('submit', function(event) {
                event.preventDefault();

                const actionUrl = this.getAttribute('action');
                fetch(actionUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        $('#confirmationModal').modal('hide');
                        fetchOrders(1, statusFilter.value, dateFrom.value, dateTo.value);
                    })
                    .catch(error => console.error('Error deleting order:', error));
            });

            statusFilter.addEventListener('change', function() {
                fetchOrders(1, this.value, dateFrom.value, dateTo.value);
            });

            dateFrom.addEventListener('change', function() {
                fetchOrders(1, statusFilter.value, this.value, dateTo.value);
            });

            dateTo.addEventListener('change', function() {
                fetchOrders(1, statusFilter.value, dateFrom.value, this.value);
            });

            fetchOrders();
        });

    </script>
@endsection
