@extends('Layouts.admin')
@section('content')
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title d-sm-flex d-block">
                                <h5>Product List</h5>
                                <div class="right-options">
                                    <ul>
                                        <li>
                                            <a class="btn btn-solid" href="{{ route('new-product') }}">Add Product</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <div class="table-responsive">
                                    <table class="table all-package theme-table table-product" id="table_id">
                                        <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Category</th>
                                            <th>Price</th>
                                            <th>Discount</th>
                                            <th>Option</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <td>
                                                    <div class="table-image">
                                                        @if($product->image)
                                                            <img src="{{ asset('storage/stickers-image/' . $product->image[0]) }}" class="img-fluid" alt="{{ $product->name }}">
                                                        @else
                                                            N/A
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name ?? 'N/A' }}</td> <!-- Displaying Category Name -->

                                                <td>{{ number_format($product->price, 2) }} MAD</td>
                                                <td>{{ $product->discount ? $product->discount . '%' : 'N/A' }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="{{ route('show-product', $product->slug) }}">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" class="delete-button"
                                                               data-delete-url="{{ route('delete-product', $product->id) }}"
                                                               data-product-name="{{ $product->name }}"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#confirmationModal">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-between align-items-center py-4 px-3">
            @php
                $showPaginationControls = !$products->onFirstPage() || $products->hasMorePages();
            @endphp

            @if ($showPaginationControls)
                <div class="text-sm text-secondary">
                    {{  $products->firstItem() }} to {{  $products->lastItem() }} from {{  $products->total() }} products
                </div>
                <nav aria-label="Page navigation">
                    <ul class="pagination mb-0">
                        <!-- First Page Button -->
                        @if (! $products->onFirstPage())
                            <li class="page-item">
                                <a class="page-link" href="{{  $products->url(1) }}" aria-label="First">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                        @endif

                        <!-- Previous Page Button -->
                        @if (! $products->onFirstPage())
                            <li class="page-item">
                                <a class="page-link" href="{{  $products->previousPageUrl() }}" aria-label="Previous">
                                    <span aria-hidden="true">&lsaquo;</span>
                                </a>
                            </li>
                        @endif

                        <!-- Current Page Indicator -->
                        <li class="page-item disabled">
                            <span class="page-link">{{  $products->currentPage() }}</span>
                        </li>

                        <!-- Next Page Button -->
                        @if ( $products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{  $products->nextPageUrl() }}" aria-label="Next">
                                    <span aria-hidden="true">&rsaquo;</span>
                                </a>
                            </li>
                        @endif

                        <!-- Last Page Button -->
                        @if ( $products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{  $products->url( $products->lastPage()) }}" aria-label="Last">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
        </div>
        @include('admin.includes.modal')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const deleteButtons = document.querySelectorAll('.delete-button');
                const deleteForm = document.getElementById('delete-form');
                const deleteMessage = document.getElementById('delete-message');

                deleteButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const deleteUrl = this.getAttribute('data-delete-url');
                        const productName = this.getAttribute('data-product-name');

                        // Set the form action to the delete URL
                        deleteForm.setAttribute('action', deleteUrl);

                        // Set the message
                        deleteMessage.innerText = `Are you sure you want to delete the "${productName}" product ?`;
                    });
                });
            });
        </script>
@endsection
