@extends('Layouts.admin')

@section('content')
    <div class="container-fluid py-5">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-10 col-md-8 m-auto">
                        <div class="card shadow-lg border-0 rounded-3">
                            <div class="card-header text-black rounded-top">
                                <h5 class="mb-0 text-center">{{ isset($product) ? 'Update Product' : 'Add New Product' }}</h5>
                            </div>
                            <div class="card-body p-4">
                                <div id="successDisplay" class="alert alert-success d-none"></div>
                                <div id="errorDisplay" class="alert alert-danger d-none">
                                    <ul id="errorList"></ul>
                                </div>

                                <form id="productForm" class="theme-form theme-form-2 mega-form">
                                    @csrf
                                    @if(isset($product))
                                        <input type="hidden" id="productId" name="productId" value="{{ $product->slug }}">
                                    @endif

                                    <!-- Category Select -->
                                    <div class="row mb-4">
                                        <label class="form-label-title col-sm-6 mb-0 fw-bold">Select Category</label>
                                        <div class="col-sm-6">
                                            <select class="form-control form-select rounded-pill" name="category_id" id="categorySelect" required>
                                                <option value="">Select a category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ isset($product) && $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                            <span id="category_id_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- Product Name -->
                                    <div class="row mb-4">
                                        <label class="form-label-title col-sm-6 mb-0 fw-bold">Product Name</label>
                                        <div class="col-sm-6">
                                            <input class="form-control rounded-pill" type="text" name="name" id="productName" placeholder="Enter Product Name" value="{{ isset($product) ? $product->name : '' }}" required>
                                            <span id="name_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="row mb-4">
                                        <label class="form-label-title col-sm-6 mb-0 fw-bold">Description</label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control rounded-3" name="description" id="productDescription" placeholder="Enter Description" rows="3" required>{{ isset($product) ? $product->description : '' }}</textarea>
                                            <span id="description_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- Image Upload -->
                                    <div class="row mb-4">
                                        <label class="form-label-title col-sm-6 mb-0 fw-bold">Product Images</label>
                                        <div class="col-sm-6">
                                            <div class="input-group">
                                                <input type="file" class="form-control" name="images[]" id="imageInput" accept="image/*" multiple {{ !isset($product) ? 'required' : '' }}>
                                            </div>
                                            <span id="images_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- Show uploaded images if updating a product -->
                                    @if(isset($product) && $product->image)
                                        <div class="row mb-4">
                                            <label class="form-label-title col-sm-6 mb-0 fw-bold">Uploaded Images</label>
                                            <div class="col-sm-6 d-flex flex-wrap">
                                                @foreach($product->image as $image)
                                                    <div class="position-relative me-2 mb-2">
                                                        <img src="{{ asset('/storage/stickers-image/' . $image) }}" alt="Product Image" class="img-thumbnail" style="width: 100px; height: 100px;">
                                                        <button type="button" class="btn btn-danger btn-sm position-absolute remove-image" style="top: 0; right: 0;" title="Remove" data-image="{{ $image }}">
                                                            <i class="ri-delete-bin-2-fill"></i>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Characteristics -->
                                    <div class="row mb-4">
                                        <label class="form-label-title col-sm-6 mb-0 fw-bold">Characteristics</label>
                                        <div class="col-sm-6">
                                            <div id="characteristicsContainer" class="d-flex align-items-center mb-2">
                                                <input type="text" class="form-control mr-2 rounded-pill" placeholder="Enter Characteristic" id="newCharacteristicInput">
                                                <button type="button" class="btn btn-light p-1 rounded-circle" id="addCharacteristic" title="Add Characteristic">
                                                    <i class="ri-add-line"></i>
                                                </button>
                                            </div>

                                            <!-- Display Existing Characteristics -->
                                            <div id="addedCharacteristics" class="d-flex flex-wrap">

                                            </div>
                                            <span id="caracteristics_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- Price -->
                                    <div class="row mb-4">
                                        <label class="form-label-title col-sm-6 mb-0 fw-bold">Price</label>
                                        <div class="col-sm-6">
                                            <input class="form-control rounded-pill" type="number" name="price" id="productPrice" placeholder="Enter Price" value="{{ isset($product) ? $product->price : '' }}" required>
                                            <span id="price_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <!-- Discount -->
                                    <div class="row mb-4">
                                        <label class="form-label-title col-sm-6 mb-0 fw-bold">Discount (%)</label>
                                        <div class="col-sm-6">
                                            <input class="form-control rounded-pill" type="number" name="discount" id="productDiscount" placeholder="Enter Discount (in %)" value="{{ isset($product) ? $product->discount : '' }}" required>
                                            <span id="discount_error" class="text-danger"></span>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-sm-6 m-auto text-center">
                                            <button type="submit" class="btn btn-primary btn-lg rounded-pill w-100">{{ isset($product) ? 'Update Product' : 'Add Product' }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize an array to hold the characteristics
        let characteristicsArray = [];

        // Add characteristics functionality
        const addCharacteristicButton = document.getElementById('addCharacteristic');
        const characteristicsContainer = document.getElementById('addedCharacteristics');

        // Function to update characteristicsArray with current input values
        function updateCharacteristicsArray() {
            const characteristicInputs = document.querySelectorAll('.characteristic-text');
            characteristicsArray = Array.from(characteristicInputs).map(input => input.value);
        }

        // Function to load incoming characteristics from the server
        function loadIncomingCharacteristics() {
            const existingCharacteristics = @json(isset($product) ? $product->caracteristics : []);
            existingCharacteristics.forEach(characteristic => {
                addCharacteristicToUI(characteristic);
            });
        }

        // Function to add a new characteristic to the UI
        function addCharacteristicToUI(characteristicValue) {
            // Check if the characteristic already exists
            if (characteristicsArray.includes(characteristicValue)) {
                alert('This characteristic already exists.');
                return;
            }

            const characteristicItem = document.createElement('div');
            characteristicItem.className = 'characteristic-item d-flex align-items-center mr-2 mb-2';

            const characteristicText = document.createElement('input');
            characteristicText.className = 'form-control mr-2 characteristic-text';
            characteristicText.value = characteristicValue;
            characteristicText.readOnly = true;

            const removeButton = document.createElement('button');
            removeButton.className = 'btn btn-danger btn-sm remove-characteristic';
            removeButton.title = 'Remove';
            removeButton.innerHTML = '<i class="ri-delete-bin-2-fill"></i>';

            removeButton.addEventListener('click', function () {
                // Remove the characteristic item from UI
                characteristicsContainer.removeChild(characteristicItem);
                // Update the characteristics array after removal
                updateCharacteristicsArray();
            });

            characteristicItem.appendChild(characteristicText);
            characteristicItem.appendChild(removeButton);

            characteristicsContainer.appendChild(characteristicItem);
            characteristicsArray.push(characteristicValue); // Update the array when added
        }

        // Load existing characteristics when the page loads
        loadIncomingCharacteristics();

        // Function to add a new characteristic from input
        addCharacteristicButton.addEventListener('click', function () {
            const newCharacteristicInput = document.getElementById('newCharacteristicInput');
            const characteristicValue = newCharacteristicInput.value.trim();

            if (characteristicValue) {
                addCharacteristicToUI(characteristicValue);
                newCharacteristicInput.value = ''; // Clear input field
            } else {
                alert('Please enter a characteristic.');
            }
        });

        // Array to keep track of removed images
        let removedImages = [];

        // Function to handle the removal of existing images
        document.querySelectorAll('.remove-image').forEach(button => {
            button.addEventListener('click', function () {
                const imageName = this.getAttribute('data-image');
                removedImages.push(imageName);
                this.closest('.position-relative').remove(); // Remove image from UI
            });
        });

        // Handle form submission
        document.getElementById('productForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);

            // Add characteristics array to form
            characteristicsArray.forEach(characteristic => {
                formData.append('caracteristics[]', characteristic);
            });

            // Add removed images individually
            removedImages.forEach(image => {
                formData.append('removed_images[]', image);
            });

            // Log characteristicsArray to console before sending the request
            console.log('Characteristics before sending:', characteristicsArray);

            const imageFiles = document.getElementById('imageInput').files;
            for (let i = 0; i < imageFiles.length; i++) {
                formData.append('image[]', imageFiles[i]);
            }

            // Check if productId exists and retrieve its value; otherwise, set it to null or handle accordingly
            const productIdElement = document.getElementById('productId');
            // Pass the route names to JavaScript
            const updateProductRoute = "{{ route('update-product', ':id') }}"; // Placeholder for the product ID
            const storeProductRoute = "{{ route('store-product') }}";

            const productId = document.getElementById('productId') ? document.getElementById('productId').value : null;

            // Use the route names in the URL
            const url = productId ? updateProductRoute.replace(':id', productId) : storeProductRoute;

            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
            })
                .then(response => response.json())
                .then(data => {
                    if (data.errors) {
                        document.getElementById('errorDisplay').classList.remove('d-none');
                        document.getElementById('errorList').innerHTML = '';
                        Object.values(data.errors).forEach(error => {
                            const li = document.createElement('li');
                            li.textContent = error[0];
                            document.getElementById('errorList').appendChild(li);
                        });
                    } else {
                        document.getElementById('successDisplay').textContent = data.message;
                        document.getElementById('successDisplay').classList.remove('d-none');
                        setTimeout(() => {
                            window.location.href = '{{route('product-list')}}';
                        }, 4000);
                    }
                })
                .catch(error => {
                    document.getElementById('errorDisplay').classList.remove('d-none');
                    document.getElementById('errorList').innerHTML = '<li>An error occurred. Please try again.</li>';
                });
        });
    </script>










@endsection
