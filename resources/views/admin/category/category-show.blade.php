@extends('Layouts.admin')
@section('content')
    <div class="container-fluid">
        <div class="container mt-4">
            <!-- First Section: Category Information -->
            <div class="col">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header text-black">
                            <h5 class="mb-0">Category Information</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('update-category', $category->slug) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <!-- Category Name -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}">
                                    @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Category Image -->
                                <div class="mb-3">
                                    <label for="image" class="form-label">Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                    @if($category->image)
                                        <img src="{{ asset('storage/categories-image/' . $category->image) }}" alt="Category Image" class="img-thumbnail mt-2" width="100">
                                    @endif
                                    @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Update Button -->
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100">Update Category</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Second Section: Products Related to the Category -->
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header text-black text-center">
                            <h5 class="mb-0">Products in {{$category->name}} Category</h5>
                        </div>
                        <div class="card-body">
                            <!-- Check if there are products -->
                            @if($category->stickers->isEmpty())
                                <div class="alert alert-warning text-center">
                                    No items available in this category. Displaying sample products.
                                </div>
                            @else
                                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
                                    @foreach($category->stickers as $product)
                                        <div class="col">
                                            <div class="card h-100 shadow-sm">
                                                <!-- Product Image -->
                                                <img src="{{ asset('storage/stickers-image/' . $product->image[0]) }}" class="card-img-top" alt="Product Image" style="object-fit: cover; height: 200px;">

                                                <div class="card-body d-flex flex-column">
                                                    <!-- Product Name -->
                                                    <h5 class="card-title">{{ $product->name }}</h5>

                                                    <!-- Product Price and Discount -->
                                                    <p class="card-text">
                                                        <strong>Price:</strong> ${{ number_format($product->price, 2) }} MAD
                                                        @if($product->discount)
                                                            <span class="text-danger">{{ $product->discount }}% off</span>
                                                        @endif
                                                    </p>

                                                    <!-- Buttons aligned at the bottom -->
                                                    <div class="mt-auto d-flex justify-content-between">
                                                        <a href="{{ route('show-product', $product->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-eye"></i> View
                                                        </a>
                                                        <a href="javascript:void(0)" class="delete-button"
                                                           data-delete-url="{{ route('delete-product', $product->id) }}"
                                                           data-product-name="{{ $product->name }}"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#confirmationModal">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Include the Confirmation Modal -->
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
